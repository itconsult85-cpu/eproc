<?php

namespace App\Controllers;

use App\Models\ClientPurchaseOrderModel;
use App\Models\BastModel;
use App\Models\CompanyModel;
use App\Models\PurchaseOrderModel;
use App\Models\QuotationModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Basts extends BaseController
{
    private BastModel $model;

    public function __construct()
    {
        $this->model = new BastModel();
    }

    public function index()
    {
        return view('basts/index', ['title' => 'Berita Acara Serah Terima']);
    }

    public function datatable()
    {
        $request = $this->request->getGet();
        $search = trim((string) ($request['search']['value'] ?? ''));
        $start = max(0, (int) ($request['start'] ?? 0));
        $length = max(1, min(100, (int) ($request['length'] ?? 10)));
        $orderIndex = (int) ($request['order'][0]['column'] ?? 7);
        $columns = ['responsive', 'number', 'bast_no', 'source_no', 'company', 'handover_date', 'recipient', 'status', 'created_at', 'actions'];
        $orderColumn = $columns[$orderIndex] ?? 'created_at';
        $direction = strtoupper((string) ($request['order'][0]['dir'] ?? 'DESC'));
        $rows = $this->model->listPage($search, $start, $length, $orderColumn, $direction);
        $data = array_map(static function (array $row): array {
            $id = public_id((int) $row['id']);
            $statusClass = ($row['status'] ?? 'draft') === 'completed' ? 'success' : 'secondary';
            $actions = '<div class="dropdown datatable-actions"><button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Aksi</button><ul class="dropdown-menu dropdown-menu-end">'
                . '<li><a class="dropdown-item" href="/basts/' . $id . '/pdf"><i class="bi bi-file-earmark-pdf me-2"></i>Unduh PDF</a></li>'
                . '<li><a class="dropdown-item" href="/basts/' . $id . '/edit"><i class="bi bi-pencil me-2"></i>Edit BAST</a></li>'
                . '<li><form method="post" action="/basts/' . $id . '/delete" data-confirm data-confirm-title="Hapus BAST?">' . csrf_field() . '<button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Hapus BAST</button></form></li></ul></div>';
            return [
                'responsive' => '',
                'bast_no' => '<strong>' . esc($row['bast_no']) . '</strong><div class="small text-body-secondary">' . esc($row['source_title'] ?? '') . '</div>',
                'source_no' => esc($row['source_no'] ?? '-'),
                'company' => esc($row['company_name'] ?? '-'),
                'handover_date' => esc($row['handover_date'] ?? '-'),
                'recipient' => esc($row['recipient_name'] ?? '-'),
                'status' => '<span class="badge text-bg-' . $statusClass . '">' . esc(ucfirst((string) ($row['status'] ?? 'draft'))) . '</span>',
                'created_at' => esc($row['created_at'] ?? '-'),
                'actions' => $actions,
            ];
        }, $rows);
        return $this->response->setJSON([
            'draw' => (int) ($request['draw'] ?? 0),
            'recordsTotal' => $this->model->countAll(),
            'recordsFiltered' => $this->model->countFiltered($search),
            'data' => $data,
        ]);
    }

    public function new()
    {
        return view('basts/form', $this->formContext());
    }

    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $bast = $this->model->find($id);
        if (! $bast) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('basts/form', $this->formContext($bast));
    }

    public function save(?string $id = null)
    {
        $id = $id ? $this->resolveId($id, $this->model) : null;
        $existing = $id ? $this->model->find($id) : null;
        $sourceType = (string) $this->request->getPost('source_type');
        $sourcePublicId = (string) $this->request->getPost('source_id');
        $sourceModel = $sourceType === 'quotation' ? new QuotationModel() : ($sourceType === 'client_purchase_order' ? new ClientPurchaseOrderModel() : ($sourceType === 'purchase_order' ? new PurchaseOrderModel() : null));
        if (! $sourceModel || $sourcePublicId === '') return redirect()->back()->withInput()->with('error', 'Sumber dokumen harus berupa quotation, PO IN klien, atau PO OUT vendor.');
        $sourceId = $this->resolveId($sourcePublicId, $sourceModel);
        $source = $sourceType === 'quotation' ? (new QuotationModel())->detail($sourceId) : ($sourceType === 'client_purchase_order' ? (new ClientPurchaseOrderModel())->detail($sourceId) : (new PurchaseOrderModel())->detail($sourceId));
        if (! $source) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $items = array_values(array_map(static fn(array $item): array => [
            'product_name' => (string) ($item['product_name'] ?? ''),
            'description' => (string) ($item['description'] ?? ''),
            'quantity' => (float) ($item['quantity'] ?? 0),
            'unit' => (string) ($item['unit'] ?? 'pcs'),
            'unit_price' => (float) ($item['unit_price'] ?? 0),
            'line_total' => (float) ($item['line_total'] ?? 0),
        ], (array) ($source['items'] ?? [])));
        if (! $items) return redirect()->back()->withInput()->with('error', 'Dokumen sumber belum memiliki item barang.');
        $companyId = in_array($sourceType, ['quotation', 'client_purchase_order'], true) ? (int) ($source['company_id'] ?? 0) : (int) $this->request->getPost('company_id');
        $company = (new CompanyModel())->find($companyId);
        $data = $this->request->getPost(['bast_no', 'handover_date', 'location', 'recipient_name', 'recipient_position', 'handed_over_by', 'handed_over_position', 'status', 'notes']);
        if (! $company || trim((string) ($data['bast_no'] ?? '')) === '' || empty($data['handover_date']) || trim((string) ($data['recipient_name'] ?? '')) === '') {
            return redirect()->back()->withInput()->with('error', 'Nomor BAST, perusahaan, tanggal, dan penerima wajib diisi.');
        }
        $duplicate = $this->model->where('bast_no', trim((string) $data['bast_no']))->first();
        if ($duplicate && (int) $duplicate['id'] !== (int) ($id ?? 0)) return redirect()->back()->withInput()->with('error', 'Nomor BAST sudah digunakan.');
        $data += [
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'source_no' => $sourceType === 'quotation' ? $source['quotation_no'] : ($sourceType === 'client_purchase_order' ? $source['po_no'] : $source['po_no']),
            'source_title' => $sourceType === 'quotation' ? $source['title'] : ($sourceType === 'client_purchase_order' ? 'PO IN Klien ' . $source['po_no'] : 'PO OUT Vendor ' . $source['po_no']),
            'source_date' => $sourceType === 'quotation' ? ($source['issue_date'] ?? null) : ($source['po_date'] ?? null),
            'company_id' => $companyId,
            'items_json' => json_encode($items, JSON_UNESCAPED_UNICODE),
            'created_by' => (string) (auth_user('username') ?? auth_user('full_name') ?? 'system'),
        ];
        if ($id) {
            $this->model->update($id, $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->model->insert($data);
        }
        return redirect()->to('/basts')->with('message', 'Berita Acara Serah Terima berhasil disimpan.');
    }

    public function pdf(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $bast = $this->model->withCompany($id);
        if (! $bast) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $bast['items'] = json_decode((string) $bast['items_json'], true) ?: [];
        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('basts/pdf', ['bast' => $bast]));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $fileName = preg_replace('/[^A-Za-z0-9._-]/', '-', (string) $bast['bast_no']) ?: 'bast';
        return $this->response->setHeader('Content-Type', 'application/pdf')->setHeader('Content-Disposition', 'attachment; filename="bast-' . $fileName . '.pdf"')->setBody($dompdf->output());
    }

    public function delete(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $this->model->delete($id);
        return redirect()->to('/basts')->with('message', 'BAST berhasil dihapus.');
    }

    private function formContext(?array $bast = null): array
    {
        $quotations = (new QuotationModel())->withCompany();
        $quotationDetails = [];
        foreach ($quotations as $quotation) {
            $detail = (new QuotationModel())->detail((int) $quotation['id']);
            if ($detail) $quotationDetails[public_id((int) $quotation['id'])] = $this->sourcePayload('quotation', $detail);
        }
        $purchaseOrders = (new PurchaseOrderModel())->withVendor();
        $purchaseOrderDetails = [];
        foreach ($purchaseOrders as $po) {
            $detail = (new PurchaseOrderModel())->detail((int) $po['id']);
            if ($detail) $purchaseOrderDetails[public_id((int) $po['id'])] = $this->sourcePayload('purchase_order', $detail);
        }
        $clientPurchaseOrders = (new ClientPurchaseOrderModel())->withQuotation();
        $clientPurchaseOrderDetails = [];
        foreach ($clientPurchaseOrders as $po) {
            $detail = (new ClientPurchaseOrderModel())->detail((int) $po['id']);
            if ($detail) $clientPurchaseOrderDetails[public_id((int) $po['id'])] = $this->sourcePayload('client_purchase_order', $detail);
        }
        return ['title' => $bast ? 'Edit Berita Acara Serah Terima' : 'Buat Berita Acara Serah Terima', 'bast' => $bast ?? [], 'companies' => (new CompanyModel())->orderBy('name')->findAll(), 'quotationSources' => $quotationDetails, 'clientPurchaseOrderSources' => $clientPurchaseOrderDetails, 'purchaseOrderSources' => $purchaseOrderDetails, 'action' => $bast ? '/basts/' . public_id((int) $bast['id']) : '/basts'];
    }

    private function sourcePayload(string $type, array $source): array
    {
        return ['type' => $type, 'source_no' => $type === 'quotation' ? $source['quotation_no'] : $source['po_no'], 'title' => $type === 'quotation' ? $source['title'] : ($type === 'client_purchase_order' ? 'PO IN Klien ' . $source['po_no'] : 'PO OUT Vendor ' . $source['po_no']), 'date' => $type === 'quotation' ? ($source['issue_date'] ?? '') : ($source['po_date'] ?? ''), 'company_id' => (int) ($source['company_id'] ?? 0), 'company_name' => in_array($type, ['quotation', 'client_purchase_order'], true) ? ($source['company_name'] ?? '') : '', 'vendor_name' => $type === 'purchase_order' ? ($source['vendor_name'] ?? '') : '', 'items' => $source['items'] ?? []];
    }
}
