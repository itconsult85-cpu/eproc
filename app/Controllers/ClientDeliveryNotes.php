<?php

namespace App\Controllers;

use App\Models\ClientDeliveryNoteModel;
use App\Models\ClientPurchaseOrderModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ClientDeliveryNotes extends BaseController
{
    private ClientDeliveryNoteModel $model;

    public function __construct() { $this->model = new ClientDeliveryNoteModel(); }

    public function index() { return view('client_delivery_notes/index', ['title' => 'Surat Jalan Client']); }

    public function datatable()
    {
        $request = $this->request->getGet(); $search = trim((string) ($request['search']['value'] ?? '')); $start = max(0, (int) ($request['start'] ?? 0)); $length = max(1, min(100, (int) ($request['length'] ?? 10))); $columns = ['responsive', 'number', 'delivery_no', 'client_po_no', 'company', 'delivery_date', 'recipient', 'status', 'created_at', 'actions']; $orderIndex = (int) ($request['order'][0]['column'] ?? 8);
        $rows = $this->model->listPage($search, $start, $length, $columns[$orderIndex] ?? 'created_at', strtoupper((string) ($request['order'][0]['dir'] ?? 'DESC')));
        $data = array_map(static function (array $row): array {
            $id = public_id((int) $row['id']); $status = (string) ($row['status'] ?? 'draft'); $statusClass = $status === 'delivered' ? 'success' : ($status === 'cancelled' ? 'danger' : 'secondary');
            $actions = '<div class="dropdown datatable-actions"><button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Aksi</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="/client-delivery-notes/' . $id . '/pdf"><i class="bi bi-file-earmark-pdf me-2"></i>Unduh PDF</a></li><li><a class="dropdown-item" href="/client-delivery-notes/' . $id . '/edit"><i class="bi bi-pencil me-2"></i>Edit surat jalan</a></li><li><form method="post" action="/client-delivery-notes/' . $id . '/delete" data-confirm data-confirm-title="Hapus surat jalan?">' . csrf_field() . '<button class="dropdown-item text-danger"><i class="bi bi-trash3 me-2"></i>Hapus surat jalan</button></form></li></ul></div>';
            return ['responsive' => '', 'delivery_no' => '<strong>' . esc($row['delivery_no']) . '</strong>', 'client_po_no' => esc($row['client_po_no'] ?? '-'), 'company' => esc($row['company_name'] ?? '-'), 'delivery_date' => esc($row['delivery_date'] ?? '-'), 'recipient' => esc($row['recipient_name'] ?? '-'), 'status' => '<span class="badge text-bg-' . $statusClass . '">' . esc(ucfirst($status)) . '</span>', 'created_at' => esc($row['created_at'] ?? '-'), 'actions' => $actions];
        }, $rows);
        return $this->response->setJSON(['draw' => (int) ($request['draw'] ?? 0), 'recordsTotal' => $this->model->countAll(), 'recordsFiltered' => $this->model->countFiltered($search), 'data' => $data]);
    }

    public function new() { return view('client_delivery_notes/form', $this->formContext()); }

    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->model); $delivery = $this->model->detail($id);
        if (! $delivery) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('client_delivery_notes/form', $this->formContext($delivery));
    }

    public function save(?string $id = null)
    {
        $id = $id ? $this->resolveId($id, $this->model) : null; $poPublicId = (string) $this->request->getPost('client_purchase_order_id');
        if ($poPublicId === '') return redirect()->back()->withInput()->with('error', 'PO IN klien wajib dipilih.');
        $poModel = new ClientPurchaseOrderModel(); $poId = $this->resolveId($poPublicId, $poModel); $po = $poModel->detail($poId);
        if (! $po || in_array($po['status'], ['cancelled', 'draft'], true)) return redirect()->back()->withInput()->with('error', 'Surat jalan hanya boleh dibuat dari PO IN yang diterima atau dikonfirmasi.');
        $items = array_values(array_map(static fn(array $item): array => ['product_name' => (string) ($item['product_name'] ?? ''), 'description' => (string) ($item['description'] ?? ''), 'quantity' => (float) ($item['quantity'] ?? 0), 'unit' => (string) ($item['unit'] ?? 'pcs')], (array) ($po['items'] ?? [])));
        if (! $items) return redirect()->back()->withInput()->with('error', 'PO IN belum memiliki item barang.');
        $data = $this->request->getPost(['delivery_no', 'delivery_date', 'destination', 'recipient_name', 'recipient_position', 'delivered_by', 'delivered_position', 'status', 'notes']); $data['delivery_no'] = trim((string) ($data['delivery_no'] ?? ''));
        if ($data['delivery_no'] === '' || empty($data['delivery_date']) || trim((string) ($data['recipient_name'] ?? '')) === '' || ! in_array($data['status'] ?? '', ['draft', 'sent', 'delivered', 'cancelled'], true)) return redirect()->back()->withInput()->with('error', 'Nomor, tanggal, penerima, dan status surat jalan wajib valid.');
        $duplicate = $this->model->where('delivery_no', $data['delivery_no'])->first(); if ($duplicate && (int) $duplicate['id'] !== (int) ($id ?? 0)) return redirect()->back()->withInput()->with('error', 'Nomor surat jalan sudah digunakan.');
        $data += ['client_purchase_order_id' => $poId, 'company_id' => (int) $po['company_id'], 'items_json' => json_encode($items, JSON_UNESCAPED_UNICODE), 'created_by' => (string) (auth_user('username') ?? auth_user('full_name') ?? 'system'), 'created_at' => date('Y-m-d H:i:s')];
        if ($id) $this->model->update($id, $data); else $this->model->insert($data);
        return redirect()->to('/client-delivery-notes')->with('message', 'Surat jalan client berhasil disimpan.');
    }

    public function pdf(string $id)
    {
        $id = $this->resolveId($id, $this->model); $delivery = $this->model->detail($id);
        if (! $delivery) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $delivery['items'] = json_decode((string) $delivery['items_json'], true) ?: [];
        $options = new Options(); $options->set('isRemoteEnabled', true); $dompdf = new Dompdf($options); $dompdf->loadHtml(view('client_delivery_notes/pdf', ['delivery' => $delivery])); $dompdf->setPaper('A4', 'portrait'); $dompdf->render();
        $fileName = preg_replace('/[^A-Za-z0-9._-]/', '-', (string) $delivery['delivery_no']) ?: 'surat-jalan';
        return $this->response->setHeader('Content-Type', 'application/pdf')->setHeader('Content-Disposition', 'attachment; filename="surat-jalan-' . $fileName . '.pdf"')->setBody($dompdf->output());
    }

    public function delete(string $id) { $id = $this->resolveId($id, $this->model); $this->model->delete($id); return redirect()->to('/client-delivery-notes')->with('message', 'Surat jalan client berhasil dihapus.'); }

    private function formContext(?array $delivery = null): array
    {
        $pos = (new ClientPurchaseOrderModel())->withQuotation(); $poDetails = [];
        foreach ($pos as $po) { if (in_array($po['status'], ['cancelled', 'draft'], true)) continue; $detail = (new ClientPurchaseOrderModel())->detail((int) $po['id']); if ($detail) $poDetails[public_id((int) $po['id'])] = $detail; }
        return ['title' => $delivery ? 'Edit Surat Jalan Client' : 'Buat Surat Jalan Client', 'delivery' => $delivery ?? [], 'purchaseOrders' => $poDetails, 'action' => $delivery ? '/client-delivery-notes/' . public_id((int) $delivery['id']) : '/client-delivery-notes'];
    }
}
