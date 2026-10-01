<?php

namespace App\Controllers;

use App\Models\ClientPurchaseOrderModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\PurchaseOrderModel;
use App\Models\VendorModel;

class PurchaseOrders extends BaseController
{
    private PurchaseOrderModel $model;

    public function __construct()
    {
        $this->model = new PurchaseOrderModel();
    }

    public function index()
    {
        return view('purchase_orders/index', ['title' => 'PO OUT Vendor']);
    }

    public function datatable()
    {
        $request = $this->request->getGet();
        $search = trim((string) ($request['search']['value'] ?? ''));
        $start = max(0, (int) ($request['start'] ?? 0));
        $length = max(1, min(100, (int) ($request['length'] ?? 10)));
        $columns = ['responsive', 'number', 'po_no', 'client_po', 'vendor', 'date', 'amount', 'status', 'actions'];
        $orderIndex = (int) ($request['order'][0]['column'] ?? 6);
        $rows = $this->model->listPage($search, $start, $length, $columns[$orderIndex] ?? 'created_at', strtoupper((string) ($request['order'][0]['dir'] ?? 'DESC')));
        $data = array_map(static function (array $row): array {
            $id = public_id((int) $row['id']);
            $statusClass = $row['status'] === 'approved' ? 'success' : ($row['status'] === 'cancelled' ? 'danger' : 'secondary');
            $actions = '<div class="dropdown datatable-actions"><button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Aksi</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="/purchase-orders/' . $id . '/edit"><i class="bi bi-pencil me-2"></i>Edit PO OUT</a></li><li><form method="post" action="/purchase-orders/' . $id . '/delete" data-confirm data-confirm-title="Hapus PO OUT?">' . csrf_field() . '<button class="dropdown-item text-danger"><i class="bi bi-trash3 me-2"></i>Hapus PO OUT</button></form></li></ul></div>';
            return ['po_no' => '<strong>' . esc($row['po_no']) . '</strong>', 'client_po' => esc($row['client_po_no'] ?? '-'), 'vendor' => esc($row['vendor_name']), 'date' => esc($row['po_date']), 'amount' => 'Rp ' . number_format((float) $row['grand_total'], 0, ',', '.'), 'status' => '<span class="badge text-bg-' . $statusClass . '">' . esc(ucfirst($row['status'])) . '</span>', 'actions' => $actions];
        }, $rows);
        return $this->response->setJSON(['draw' => (int) ($request['draw'] ?? 0), 'recordsTotal' => $this->model->countAll(), 'recordsFiltered' => $this->model->countFiltered($search), 'data' => $data]);
    }

    public function new()
    {
        return view('purchase_orders/form', $this->formContext([], '/purchase-orders'));
    }

    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $po = $this->model->detail($id);
        if (! $po) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('purchase_orders/form', $this->formContext($po, '/purchase-orders/' . public_id($id)));
    }

    public function save(?string $id = null)
    {
        $id = $id ? $this->resolveId($id, $this->model) : null;
        $productMap = [];
        foreach ((new ProductModel())->findAll() as $product) $productMap[(int) $product['id']] = $product;
        $valid = [];
        $subtotal = 0;
        foreach ((array) $this->request->getPost('items') as $item) {
            $product = $productMap[(int) ($item['product_id'] ?? 0)] ?? null;
            $qty = (float) ($item['quantity'] ?? 0);
            $price = (float) ($item['unit_price'] ?? 0);
            if (! $product || $qty <= 0 || $price < 0) continue;
            $line = $qty * $price;
            $subtotal += $line;
            $valid[] = ['product_id' => (int) $product['id'], 'product_name' => ($product['brand'] ? $product['brand'] . ' / ' : '') . $product['name'], 'description' => trim((string) ($item['description'] ?? $product['description'] ?? '')), 'quantity' => $qty, 'unit' => trim((string) ($item['unit'] ?? 'pcs')) ?: 'pcs', 'unit_price' => $price, 'line_total' => $line];
        }
        $vendorId = (int) $this->request->getPost('vendor_id');
        $clientPoId = (int) $this->request->getPost('client_purchase_order_id');
        if (! $vendorId || ! $valid) return redirect()->back()->withInput()->with('errors', ['items' => 'Vendor dan minimal satu produk katalog wajib dipilih.']);
        if ($clientPoId && ! (new ClientPurchaseOrderModel())->find($clientPoId)) return redirect()->back()->withInput()->with('error', 'PO IN klien tidak valid.');
        $usePpn = $this->request->getPost('use_ppn') ? 1 : 0;
        $ppn = max(0, min(100, (float) $this->request->getPost('ppn_percent')));
        $usePph = $this->request->getPost('use_pph') ? 1 : 0;
        $pph = max(0, min(100, (float) $this->request->getPost('pph_percent')));
        $ppnAmount = $usePpn ? $subtotal * $ppn / 100 : 0;
        $pphAmount = $usePph ? $subtotal * $pph / 100 : 0;
        $data = $this->request->getPost(['vendor_id', 'client_purchase_order_id', 'po_no', 'po_date', 'expected_date', 'status', 'notes']);
        $data += ['use_ppn' => $usePpn, 'ppn_percent' => $ppn, 'tax_percent' => $ppn, 'use_pph' => $usePph, 'pph_percent' => $pph, 'pph_amount' => $pphAmount, 'tax_amount' => $ppnAmount, 'subtotal' => $subtotal, 'grand_total' => $subtotal + $ppnAmount - $pphAmount, 'created_at' => date('Y-m-d H:i:s')];
        if (trim((string) ($data['po_no'] ?? '')) === '' || ! in_array($data['status'] ?? '', ['draft', 'sent', 'approved', 'cancelled'], true)) return redirect()->back()->withInput()->with('error', 'Nomor dan status PO OUT wajib valid.');
        $duplicate = $this->model->where('po_no', trim((string) $data['po_no']))->first();
        if ($duplicate && (int) $duplicate['id'] !== (int) ($id ?? 0)) return redirect()->back()->withInput()->with('error', 'Nomor PO OUT sudah digunakan.');
        $db = db_connect();
        $db->transStart();
        if ($id) {
            $this->model->update($id, $data);
            $poId = $id;
            (new PurchaseOrderItemModel())->where('purchase_order_id', $id)->delete();
        } else {
            $poId = $this->model->insert($data, true);
        }
        foreach ($valid as &$item) $item['purchase_order_id'] = $poId;
        (new PurchaseOrderItemModel())->insertBatch($valid);
        $db->transComplete();
        if ($db->transStatus() === false) return redirect()->back()->withInput()->with('error', 'PO OUT gagal disimpan.');
        return redirect()->to('/purchase-orders')->with('message', 'PO OUT ke vendor berhasil disimpan.');
    }

    public function delete(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $this->model->delete($id);
        return redirect()->to('/purchase-orders')->with('message', 'PO OUT berhasil dihapus.');
    }

    private function formContext(array $po, string $action): array
    {
        return ['title' => empty($po) ? 'Buat PO OUT ke Vendor' : 'Edit PO OUT ke Vendor', 'po' => $po, 'vendors' => (new VendorModel())->where('is_active', 1)->findAll(), 'products' => (new ProductModel())->orderBy('name')->findAll(), 'clientPurchaseOrders' => (new ClientPurchaseOrderModel())->withQuotation(), 'action' => $action];
    }
}
