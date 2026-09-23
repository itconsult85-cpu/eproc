<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProductModel;
use App\Models\QuotationItemModel;
use App\Models\QuotationModel;
use App\Models\QuotationSettingModel;
use App\Models\QuotationStatusLogModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Quotations extends BaseController
{
    private QuotationModel $model;

    public function __construct()
    {
        $this->model = new QuotationModel();
    }

    public function index()
    {
        $this->model->expireOverdue();
        return view('quotations/index', ['title' => 'Penawaran']);
    }

    public function datatable()
    {
        $this->model->expireOverdue();
        $request = $this->request->getGet();
        $draw = (int) ($request['draw'] ?? 0);
        $start = max(0, (int) ($request['start'] ?? 0));
        $length = min(100, max(1, (int) ($request['length'] ?? 10)));
        $search = trim((string) ($request['search']['value'] ?? ''));
        $total = $this->model->countAll();
        $builder = $this->model->builder()->select('quotations.*, companies.name AS company_name')->join('companies', 'companies.id = quotations.company_id', 'left');
        if ($search !== '') {
            $builder->groupStart()->like('quotations.quotation_no', $search)->orLike('companies.name', $search)->orLike('quotations.title', $search)->orLike('quotations.status', $search)->groupEnd();
        }
        $filtered = $builder->countAllResults(false);
        // Indeks mengikuti kolom tabel: kontrol, nomor, no quotation, perusahaan, judul, total, status, dibuat, aksi.
        $columns = ['created_at', 'created_at', 'quotation_no', 'company_name', 'title', 'grand_total', 'status', 'created_at', 'created_at'];
        $orderColumn = (int) ($request['order'][0]['column'] ?? 7);
        $orderDirection = strtolower((string) ($request['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';
        $builder->orderBy($columns[$orderColumn] ?? 'created_at', $orderDirection);
        $rows = $builder->get($length, $start)->getResultArray();
        $data = array_map(static function (array $row): array {
            $statusClass = ['draft' => 'secondary', 'sent' => 'primary', 'approved' => 'success', 'rejected' => 'danger', 'expired' => 'warning'][$row['status']] ?? 'secondary';
            $actions = '<div class="btn-group btn-group-sm" role="group" aria-label="Aksi penawaran">'
                . '<a class="btn btn-outline-primary" href="/quotations/' . (int) $row['id'] . '" title="Lihat" aria-label="Lihat"><i class="bi bi-eye"></i></a>'
                . '<a class="btn btn-outline-warning" href="/quotations/' . (int) $row['id'] . '/edit" title="Edit" aria-label="Edit"><i class="bi bi-pencil"></i></a>'
                . '<a class="btn btn-outline-success" href="/quotations/' . (int) $row['id'] . '/catalog/preview" title="Preview katalog produk"><i class="bi bi-journal-richtext"></i></a>'
                . '<form method="post" action="/quotations/' . (int) $row['id'] . '/delete" onsubmit="return confirm(\'Hapus penawaran ini?\')"><button class="btn btn-outline-danger" title="Hapus" aria-label="Hapus"><i class="bi bi-trash3"></i></button></form></div>';
            $statusActions = '';
            if ($row['status'] === 'draft') {
                $statusActions .= '<form class="d-inline" method="post" action="/quotations/' . (int) $row['id'] . '/status"><input type="hidden" name="status" value="sent"><button class="btn btn-sm btn-outline-primary" title="Tandai terkirim" onclick="return confirm(\'Ubah status menjadi terkirim?\')"><i class="bi bi-send"></i></button></form>';
                $statusActions .= '<form class="d-inline" method="post" action="/quotations/' . (int) $row['id'] . '/status"><input type="hidden" name="status" value="approved"><button class="btn btn-sm btn-outline-success" title="Setujui" onclick="return confirm(\'Ubah status menjadi disetujui?\')"><i class="bi bi-check2-circle"></i></button></form>';
            } elseif ($row['status'] === 'sent') {
                $statusActions .= '<form class="d-inline" method="post" action="/quotations/' . (int) $row['id'] . '/status"><input type="hidden" name="status" value="approved"><button class="btn btn-sm btn-outline-success" title="Setujui" onclick="return confirm(\'Ubah status menjadi disetujui?\')"><i class="bi bi-check2-circle"></i></button></form>';
                $statusActions .= '<form class="d-inline" method="post" action="/quotations/' . (int) $row['id'] . '/status"><input type="hidden" name="status" value="rejected"><button class="btn btn-sm btn-outline-danger" title="Tolak" onclick="return confirm(\'Ubah status menjadi ditolak?\')"><i class="bi bi-x-circle"></i></button></form>';
            }
            $actions = '<div class="d-flex flex-wrap gap-1">' . $statusActions . $actions . '</div>';
            return ['id' => (int) $row['id'], 'quotation_no' => '<strong>' . esc($row['quotation_no']) . '</strong>', 'company_name' => esc($row['company_name'] ?: '-'), 'title' => esc($row['title']), 'grand_total' => 'Rp ' . number_format((float) $row['grand_total'], 0, ',', '.'), 'status' => '<span class="badge text-bg-' . $statusClass . '">' . esc(ucfirst($row['status'])) . '</span>', 'created_at' => esc($row['created_at'] ?? '-'), 'actions' => $actions];
        }, $rows);
        return $this->response->setJSON(['draw' => $draw, 'recordsTotal' => $total, 'recordsFiltered' => $filtered, 'data' => $data]);
    }

    public function new()
    {
        return view('quotations/form', [
            'title' => 'Buat Penawaran',
            'settings' => (new QuotationSettingModel())->current(),
            'companies' => (new CompanyModel())->orderBy('name')->findAll(),
            'products' => (new ProductModel())->where('is_active', 1)->orderBy('name')->findAll(),
        ]);
    }

    public function create()
    {
        $rules = ['company_id' => 'required|is_natural_no_zero', 'title' => 'required|max_length[220]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $company = (new CompanyModel())->find((int) $this->request->getPost('company_id'));
        $customerName = trim((string) $this->request->getPost('customer_name'));
        $customerAddress = trim((string) $this->request->getPost('customer_address'));
        if ($company) {
            $customerName = $customerName !== '' ? $customerName : (string) ($company['name'] ?? '');
            $customerAddress = $customerAddress !== '' ? $customerAddress : (string) ($company['address'] ?? '');
        }
        $settings = (new QuotationSettingModel())->current();
        $products = new ProductModel();
        $items = [];
        $subtotal = 0;
        foreach ((array) $this->request->getPost('items') as $item) {
            $product = $products->find((int) ($item['product_id'] ?? 0));
            $qty = max(0, (float) ($item['quantity'] ?? 0));
            if (! $product || $qty <= 0) {
                continue;
            }
            $price = max(0, (float) ($item['unit_price'] ?? $product['selling_price']));
            $discount = max(0, min(100, (float) ($item['discount_percent'] ?? 0)));
            $line = $qty * $price * (1 - $discount / 100);
            $subtotal += $line;
            $items[] = ['product_id' => $product['id'], 'product_name' => $product['name'], 'description' => trim((string) ($item['description'] ?? '')) ?: $product['description'], 'quantity' => $qty, 'unit' => $item['unit'] ?? 'pcs', 'unit_price' => $price, 'discount_percent' => $discount, 'line_total' => $line];
        }
        $issueDate = $this->request->getPost('issue_date') ?: date('Y-m-d');
        $db = db_connect();
        $db->transStart();
        $quotationNo = $this->model->nextQuotationNumber((int) $this->request->getPost('company_id'), date('Y-m-d'));
        $validityDays = max(0, (int) ($this->request->getPost('validity_days') ?: $settings['default_validity_days'] ?? 10));
        $validUntil = date('Y-m-d', strtotime($issueDate . ' +' . $validityDays . ' days'));
        $taxPercent = max(0, (float) ($this->request->getPost('tax_percent') ?: $settings['default_tax_percent'] ?? 0));
        $tax = $subtotal * $taxPercent / 100;
        $quotationId = $this->model->insert([
            'company_id' => $this->request->getPost('company_id'),
            'quotation_no' => $quotationNo,
            'customer_name' => $customerName,
            'customer_address' => $customerAddress,
            'customer_phone' => $this->request->getPost('customer_phone'),
            'attention' => $this->request->getPost('attention'),
            'title' => $this->request->getPost('title'),
            'issue_date' => $issueDate,
            'valid_until' => $validUntil,
            'validity_days' => $validityDays,
            'payment_terms' => $this->request->getPost('payment_terms') ?: ($settings['default_payment_terms'] ?? null),
            'delivery_terms' => $this->request->getPost('delivery_terms') ?: ($settings['default_delivery_terms'] ?? null),
            'notes' => $this->request->getPost('notes'),
            'status' => 'draft',
            'subtotal' => $subtotal,
            'tax_percent' => $taxPercent,
            'tax_amount' => $tax,
            'grand_total' => $subtotal + $tax,
        ]);
        (new QuotationStatusLogModel())->insert([
            'quotation_id' => $quotationId,
            'from_status' => null,
            'to_status' => 'draft',
            'changed_by' => (string) (session()->get('username') ?: 'system'),
            'reason' => 'Quotation dibuat.',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        if ($items) {
            foreach ($items as &$item) {
                $item['quotation_id'] = $quotationId;
            }
            (new QuotationItemModel())->insertBatch($items);
        }
        $db->transComplete();
        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errors', ['quotation_no' => 'Nomor quotation gagal dibuat. Silakan coba lagi.']);
        }
        return redirect()->to('/quotations/' . $quotationId)->with('message', 'Penawaran berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $quotation = $this->model->detail($id);
        if (! $quotation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('quotations/form', [
            'title' => 'Edit Penawaran',
            'quotation' => $quotation,
            'settings' => (new QuotationSettingModel())->current(),
            'companies' => (new CompanyModel())->orderBy('name')->findAll(),
            'products' => (new ProductModel())->where('is_active', 1)->orderBy('name')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $rules = ['company_id' => 'required', 'title' => 'required'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $company = (new CompanyModel())->find((int) $this->request->getPost('company_id'));
        $customerName = trim((string) $this->request->getPost('customer_name'));
        $customerAddress = trim((string) $this->request->getPost('customer_address'));
        if ($company) {
            $customerName = $customerName !== '' ? $customerName : (string) ($company['name'] ?? '');
            $customerAddress = $customerAddress !== '' ? $customerAddress : (string) ($company['address'] ?? '');
        }

        $settings = (new QuotationSettingModel())->current();
        $products = new ProductModel();
        $items = [];
        $subtotal = 0;

        foreach ((array) $this->request->getPost('items') as $item) {
            $product = $products->find((int) ($item['product_id'] ?? 0));
            $qty = max(0, (float) ($item['quantity'] ?? 0));
            if (! $product || $qty <= 0) continue;

            $price = max(0, (float) ($item['unit_price'] ?? $product['selling_price']));
            $discount = max(0, min(100, (float) ($item['discount_percent'] ?? 0)));
            $line = $qty * $price * (1 - $discount / 100);
            $subtotal += $line;
            $items[] = ['quotation_id' => $id, 'product_id' => $product['id'], 'product_name' => $product['name'], 'description' => trim((string) ($item['description'] ?? '')) ?: $product['description'], 'quantity' => $qty, 'unit' => $item['unit'] ?? 'pcs', 'unit_price' => $price, 'discount_percent' => $discount, 'line_total' => $line];
        }

        $issueDate = $this->request->getPost('issue_date') ?: date('Y-m-d');
        $validityDays = max(0, (int) ($this->request->getPost('validity_days') ?: $settings['default_validity_days'] ?? 10));
        $taxPercent = max(0, (float) ($this->request->getPost('tax_percent') ?: $settings['default_tax_percent'] ?? 0));
        $tax = $subtotal * $taxPercent / 100;

        $this->model->update($id, [
            'company_id' => $this->request->getPost('company_id'),
            'quotation_no' => $this->model->find($id)['quotation_no'],
            'customer_name' => $customerName,
            'customer_address' => $customerAddress,
            'customer_phone' => $this->request->getPost('customer_phone'),
            'attention' => $this->request->getPost('attention'),
            'title' => $this->request->getPost('title'),
            'issue_date' => $issueDate,
            'valid_until' => date('Y-m-d', strtotime($issueDate . ' +' . $validityDays . ' days')),
            'validity_days' => $validityDays,
            'payment_terms' => $this->request->getPost('payment_terms'),
            'delivery_terms' => $this->request->getPost('delivery_terms'),
            'notes' => $this->request->getPost('notes'),
            'subtotal' => $subtotal,
            'tax_percent' => $taxPercent,
            'tax_amount' => $tax,
            'grand_total' => $subtotal + $tax,
        ]);

        (new QuotationItemModel())->where('quotation_id', $id)->delete();
        if ($items) {
            (new QuotationItemModel())->insertBatch($items);
        }
        return redirect()->to('/quotations/' . $id)->with('message', 'Penawaran berhasil diperbarui.');
    }

    public function changeStatus(int $id)
    {
        $status = strtolower(trim((string) $this->request->getPost('status')));
        $allowed = ['sent', 'approved', 'rejected'];
        if (! in_array($status, $allowed, true)) {
            return redirect()->back()->with('errors', ['status' => 'Status tujuan tidak valid.']);
        }

        try {
            $changedBy = (string) (session()->get('username') ?: 'system');
            $this->model->changeStatus($id, $status, $changedBy, 'Diubah melalui tombol aksi quotation.');
            return redirect()->to('/quotations')->with('message', 'Status quotation berhasil diubah menjadi ' . $status . '.');
        } catch (\InvalidArgumentException | \RuntimeException $exception) {
            return redirect()->back()->with('errors', ['status' => $exception->getMessage()]);
        }
    }

    public function show(int $id)
    {
        $this->model->expireOverdue();
        $quotation = $this->model->detail($id);
        if (! $quotation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $settings = (new QuotationSettingModel())->current();
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('quotations/pdf', [
            'quotation' => $quotation,
            'settings' => $settings,
            'logoData' => $this->assetData($settings['logo_path'] ?? null),
            'signatureData' => $this->assetData($settings['signature_path'] ?? null),
            'stampData' => $this->assetData($settings['stamp_path'] ?? null)
        ]));

        // Atur ukuran kertas
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF dengan Disposition 'inline' agar tampil di browser, bukan di-download otomatis
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="quotation-' . $quotation['quotation_no'] . '.pdf"')
            ->setBody($dompdf->output());
    }

    public function pdf(int $id)
    {
        $this->model->expireOverdue();
        $quotation = $this->model->detail($id);
        if (! $quotation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $settings = (new QuotationSettingModel())->current();
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('quotations/pdf', ['quotation' => $quotation, 'settings' => $settings, 'logoData' => $this->assetData($settings['logo_path'] ?? null), 'signatureData' => $this->assetData($settings['signature_path'] ?? null), 'stampData' => $this->assetData($settings['stamp_path'] ?? null)]));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $this->response->setHeader('Content-Type', 'application/pdf')->setHeader('Content-Disposition', 'attachment; filename="quotation-' . $quotation['quotation_no'] . '.pdf"')->setBody($dompdf->output());
    }

    public function catalogPreview(int $id)
    {
        $this->model->expireOverdue();
        $context = $this->catalogContext($id);
        return view('quotations/catalog_preview', $context);
    }

    public function catalog(int $id)
    {
        $this->model->expireOverdue();
        $context = $this->catalogContext($id);
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('quotations/catalog', $context));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="katalog-' . $context['quotation']['quotation_no'] . '.pdf"')
            ->setBody($dompdf->output());
    }

    private function catalogContext(int $id): array
    {
        $quotation = $this->model->detail($id);
        if (! $quotation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $products = new ProductModel();
        $items = [];
        foreach ($quotation['items'] as $item) {
            $product = ! empty($item['product_id']) ? $products->find((int) $item['product_id']) : null;
            $imageData = $product ? $this->assetData($product['image_path'] ?? null) : null;
            if (! $imageData && $product && ! empty($product['image_url'])) {
                $imageData = $product['image_url'];
            }
            $items[] = ['item' => $item, 'product' => $product, 'imageData' => $imageData];
        }

        $settings = (new QuotationSettingModel())->current();
        return [
            'quotation' => $quotation,
            'settings' => $settings,
            'items' => $items,
            'logoData' => $this->assetData($settings['logo_path'] ?? null),
        ];
    }

    private function assetData(?string $path): ?string
    {
        if (! $path || ! is_file(FCPATH . ltrim($path, '/'))) {
            return null;
        }
        $file = FCPATH . ltrim($path, '/');
        $mime = mime_content_type($file) ?: 'image/png';
        return 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($file));
    }

    public function delete(int $id)
    {
        $this->model->delete($id);
        return redirect()->to('/quotations')->with('message', 'Penawaran berhasil dihapus.');
    }
}
