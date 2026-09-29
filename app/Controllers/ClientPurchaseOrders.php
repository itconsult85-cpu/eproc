<?php

namespace App\Controllers;

use App\Models\ClientPurchaseOrderItemModel;
use App\Models\ClientPurchaseOrderModel;
use App\Models\QuotationModel;

class ClientPurchaseOrders extends BaseController
{
    private ClientPurchaseOrderModel $model;

    public function __construct()
    {
        $this->model = new ClientPurchaseOrderModel();
    }

    public function index()
    {
        return view('client_purchase_orders/index', ['title' => 'PO IN Klien']);
    }

    public function datatable()
    {
        $request = $this->request->getGet();
        $search = trim((string) ($request['search']['value'] ?? ''));
        $start = max(0, (int) ($request['start'] ?? 0));
        $length = max(1, min(100, (int) ($request['length'] ?? 10)));
        $orderIndex = (int) ($request['order'][0]['column'] ?? 8);
        $columns = ['responsive', 'number', 'po_no', 'quotation_no', 'company', 'po_date', 'grand_total', 'status', 'created_at', 'actions'];
        $rows = $this->model->listPage($search, $start, $length, $columns[$orderIndex] ?? 'created_at', strtoupper((string) ($request['order'][0]['dir'] ?? 'DESC')));
        $data = array_map(static function (array $row): array {
            $id = public_id((int) $row['id']);
            $statusClass = ($row['status'] ?? 'draft') === 'received' ? 'success' : (($row['status'] ?? '') === 'cancelled' ? 'danger' : 'secondary');
            $actions = '<div class="dropdown datatable-actions"><button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Aksi</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="/client-purchase-orders/' . $id . '/edit"><i class="bi bi-pencil me-2"></i>Edit PO IN</a></li><li><form method="post" action="/client-purchase-orders/' . $id . '/delete" data-confirm data-confirm-title="Hapus PO IN?">' . csrf_field() . '<button class="dropdown-item text-danger"><i class="bi bi-trash3 me-2"></i>Hapus PO IN</button></form></li></ul></div>';
            return ['responsive' => '', 'po_no' => '<strong>' . esc($row['po_no']) . '</strong>', 'quotation_no' => esc($row['quotation_no'] ?? '-'), 'company' => esc($row['company_name'] ?? '-'), 'po_date' => esc($row['po_date'] ?? '-'), 'grand_total' => 'Rp ' . number_format((float) $row['grand_total'], 0, ',', '.'), 'status' => '<span class="badge text-bg-' . $statusClass . '">' . esc(ucfirst((string) $row['status'])) . '</span>', 'created_at' => esc($row['created_at'] ?? '-'), 'actions' => $actions];
        }, $rows);
        return $this->response->setJSON(['draw' => (int) ($request['draw'] ?? 0), 'recordsTotal' => $this->model->countAll(), 'recordsFiltered' => $this->model->countFiltered($search), 'data' => $data]);
    }

    public function new()
    {
        return view('client_purchase_orders/form', ['title' => 'Buat PO IN dari Klien', 'po' => [], 'quotations' => $this->approvedQuotations(), 'action' => '/client-purchase-orders']);
    }

    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $po = $this->model->detail($id);
        if (! $po) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('client_purchase_orders/form', ['title' => 'Edit PO IN Klien', 'po' => $po, 'quotations' => $this->approvedQuotations(), 'action' => '/client-purchase-orders/' . public_id($id)]);
    }

    public function save(?string $id = null)
    {
        $id = $id ? $this->resolveId($id, $this->model) : null;
        $quotationPublicId = (string) $this->request->getPost('quotation_id');
        if ($quotationPublicId === '') return redirect()->back()->withInput()->with('error', 'Quotation final wajib dipilih.');
        $quotationId = $this->resolveId($quotationPublicId, new \App\Models\QuotationModel());
        $quotation = (new QuotationModel())->detail($quotationId);
        if (! $quotation || $quotation['status'] !== 'approved') return redirect()->back()->withInput()->with('error', 'PO IN hanya boleh dibuat dari quotation yang sudah approved/final.');
        $finalSnapshot = json_decode((string) ($quotation['final_snapshot_json'] ?? ''), true);
        if (is_array($finalSnapshot)) $quotation = array_merge($quotation, $finalSnapshot);
        $items = [];
        foreach ((array) ($quotation['items'] ?? []) as $item) $items[] = ['product_id' => $item['product_id'] ?? null, 'product_name' => (string) ($item['product_name'] ?? ''), 'description' => (string) ($item['description'] ?? ''), 'quantity' => (float) ($item['quantity'] ?? 0), 'unit' => (string) ($item['unit'] ?? 'pcs'), 'unit_price' => (float) ($item['unit_price'] ?? 0), 'discount_percent' => (float) ($item['discount_percent'] ?? 0), 'line_total' => (float) ($item['line_total'] ?? 0)];
        if (! $items) return redirect()->back()->withInput()->with('error', 'Quotation belum memiliki item barang.');
        $data = $this->request->getPost(['po_no', 'po_date', 'status', 'notes']);
        $data['po_no'] = trim((string) ($data['po_no'] ?? ''));
        if ($data['po_no'] === '' || empty($data['po_date']) || ! in_array($data['status'] ?? '', ['draft', 'received', 'confirmed', 'cancelled'], true)) return redirect()->back()->withInput()->with('error', 'Nomor PO, tanggal, dan status PO IN wajib valid.');
        $duplicate = $this->model->where('po_no', $data['po_no'])->first();
        if ($duplicate && (int) $duplicate['id'] !== (int) ($id ?? 0)) return redirect()->back()->withInput()->with('error', 'Nomor PO IN sudah digunakan.');
        $data += ['quotation_id' => $quotationId, 'company_id' => (int) $quotation['company_id'], 'subtotal' => (float) ($quotation['subtotal'] ?? 0), 'tax_percent' => (float) ($quotation['tax_percent'] ?? 0), 'tax_amount' => (float) ($quotation['tax_amount'] ?? 0), 'grand_total' => (float) ($quotation['grand_total'] ?? 0), 'created_at' => date('Y-m-d H:i:s')];
        $db = db_connect();
        $db->transStart();
        if ($id) {
            $this->model->update($id, $data);
            $poId = $id;
            (new ClientPurchaseOrderItemModel())->where('client_purchase_order_id', $id)->delete();
        } else {
            $poId = $this->model->insert($data, true);
        }
        foreach ($items as &$item) $item['client_purchase_order_id'] = $poId;
        (new ClientPurchaseOrderItemModel())->insertBatch($items);
        $db->transComplete();
        if ($db->transStatus() === false) return redirect()->back()->withInput()->with('error', 'PO IN gagal disimpan.');
        return redirect()->to('/client-purchase-orders')->with('message', 'PO IN klien berhasil disimpan dari quotation final.');
    }

    public function delete(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $this->model->delete($id);
        return redirect()->to('/client-purchase-orders')->with('message', 'PO IN klien berhasil dihapus.');
    }

    private function approvedQuotations(): array
    {
        $quotations = (new QuotationModel())->select('quotations.*, companies.name AS company_name')->join('companies', 'companies.id = quotations.company_id', 'left')->where('quotations.status', 'approved')->orderBy('quotations.created_at', 'DESC')->findAll();
        foreach ($quotations as &$quotation) {
            $detail = (new QuotationModel())->detail((int) $quotation['id']);
            $snapshot = json_decode((string) ($quotation['final_snapshot_json'] ?? ''), true);
            $quotation['items'] = is_array($snapshot) && isset($snapshot['items']) ? $snapshot['items'] : ($detail['items'] ?? []);
        }
        return $quotations;
    }
}
