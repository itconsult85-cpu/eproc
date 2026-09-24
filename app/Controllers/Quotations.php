<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProductModel;
use App\Models\QuotationItemModel;
use App\Models\QuotationModel;
use App\Models\QuotationNegotiationModel;
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
            $publicId = public_id((int) $row['id']);
            $statusClass = ['draft' => 'secondary', 'sent' => 'primary', 'negotiation' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'expired' => 'warning'][$row['status']] ?? 'secondary';
            $statusMeta = [
                'draft' => ['label' => 'Draft / Belum diajukan', 'hint' => 'Siapkan lalu kirim ke proses berikutnya.'],
                'sent' => ['label' => 'Terkirim / Menunggu keputusan', 'hint' => 'Bisa disetujui atau diajukan ke negosiasi.'],
                'negotiation' => ['label' => 'Sedang negosiasi', 'hint' => 'Buka detail untuk memproses putaran nego.'],
                'approved' => ['label' => 'Final disetujui', 'hint' => 'Siap dicetak sebagai Proforma Invoice.'],
                'rejected' => ['label' => 'Ditolak', 'hint' => 'Proses quotation telah dihentikan.'],
                'expired' => ['label' => 'Kedaluwarsa', 'hint' => 'Perpanjang masa berlaku sebelum diproses.'],
            ][$row['status']] ?? ['label' => 'Status tidak dikenal', 'hint' => 'Buka detail untuk memeriksa quotation.'];
            $actions = '<li><a class="dropdown-item" href="/quotations/' . $publicId . '"><i class="bi bi-eye me-2 text-primary"></i>Detail quotation</a></li>'
                . '<li><a class="dropdown-item" href="/quotations/' . $publicId . '/edit"><i class="bi bi-pencil me-2 text-warning"></i>Edit quotation</a></li>'
                . '<li><a class="dropdown-item" href="/quotations/' . $publicId . '/catalog/preview"><i class="bi bi-journal-richtext me-2 text-success"></i>Preview katalog</a></li>';
            $statusActions = '';
            if ($row['status'] === 'draft') {
                if (empty($row['valid_until']) || $row['valid_until'] >= date('Y-m-d')) {
                    $statusActions .= '<li><form method="post" action="/quotations/' . $publicId . '/status" data-confirm data-confirm-title="Kirim quotation?" data-confirm-message="Quotation akan ditandai sebagai terkirim dan siap diproses lebih lanjut." data-confirm-label="Ya, kirim" data-confirm-variant="primary">' . csrf_field() . '<input type="hidden" name="status" value="sent"><button class="dropdown-item" type="submit"><i class="bi bi-send me-2 text-primary"></i>Kirim quotation</button></form></li>';
                } else {
                    $statusActions .= '<li><a class="dropdown-item" href="/quotations/' . $publicId . '"><i class="bi bi-clock-history me-2 text-warning"></i>Perpanjang masa berlaku</a></li>';
                }
                if (empty($row['valid_until']) || $row['valid_until'] >= date('Y-m-d')) $statusActions .= '<li><form method="post" action="/quotations/' . $publicId . '/status" data-confirm data-confirm-title="Setujui quotation?" data-confirm-message="Quotation akan menjadi final dan dapat digunakan untuk mencetak Proforma Invoice." data-confirm-label="Ya, setujui" data-confirm-variant="success">' . csrf_field() . '<input type="hidden" name="status" value="approved"><button class="dropdown-item" type="submit"><i class="bi bi-check2-circle me-2 text-success"></i>Setujui sebagai final</button></form></li>';
            } elseif ($row['status'] === 'sent') {
                $statusActions .= '<li><form method="post" action="/quotations/' . $publicId . '/status" data-confirm data-confirm-title="Setujui quotation?" data-confirm-message="Quotation akan menjadi final dan dapat digunakan untuk mencetak Proforma Invoice." data-confirm-label="Ya, setujui" data-confirm-variant="success">' . csrf_field() . '<input type="hidden" name="status" value="approved"><button class="dropdown-item" type="submit"><i class="bi bi-check2-circle me-2 text-success"></i>Setujui sebagai final</button></form></li>'
                    . '<li><form method="post" action="/quotations/' . $publicId . '/status" data-confirm data-confirm-title="Tolak quotation?" data-confirm-message="Quotation akan ditandai sebagai ditolak dan tidak dapat diproses sebagai quotation final." data-confirm-label="Ya, tolak" data-confirm-variant="danger">' . csrf_field() . '<input type="hidden" name="status" value="rejected"><button class="dropdown-item text-danger" type="submit"><i class="bi bi-x-circle me-2"></i>Tolak quotation</button></form></li>';
            } elseif ($row['status'] === 'expired') {
                $statusActions .= '<li><a class="dropdown-item" href="/quotations/' . $publicId . '"><i class="bi bi-clock-history me-2 text-warning"></i>Perpanjang masa berlaku</a></li>';
            }
            if (in_array($row['status'], ['draft', 'sent', 'negotiation'], true)) {
                $statusActions = '<li><hr class="dropdown-divider"></li>' . $statusActions;
                $statusActions .= '<li><a class="dropdown-item" href="/quotations/' . $publicId . '"><i class="bi bi-chat-square-text me-2 text-warning"></i>Proses negosiasi</a></li>';
            }
            $deleteAction = '<li><hr class="dropdown-divider"></li><li><form method="post" action="/quotations/' . $publicId . '/delete" data-confirm data-confirm-title="Hapus quotation?" data-confirm-message="Quotation ini akan dihapus dan tidak dapat dipulihkan." data-confirm-label="Ya, hapus" data-confirm-variant="danger">' . csrf_field() . '<button class="dropdown-item text-danger" type="submit"><i class="bi bi-trash3 me-2"></i>Hapus quotation</button></form></li>';
            $actions = '<div class="dropdown quotation-actions"><button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false"><i class="bi bi-three-dots me-1"></i>Aksi</button><ul class="dropdown-menu dropdown-menu-end shadow-sm">' . $actions . $statusActions . $deleteAction . '</ul></div>';
            $pastDue = ! empty($row['valid_until']) && $row['valid_until'] < date('Y-m-d') && in_array($row['status'], ['draft', 'sent', 'negotiation', 'expired'], true);
            $statusLabel = '<span class="d-inline-block"><span class="badge text-bg-' . $statusClass . '">' . esc($statusMeta['label']) . '</span><small class="quotation-status-hint d-block">' . esc($statusMeta['hint']) . '</small></span>' . ($pastDue ? '<small class="text-danger d-block">Masa berlaku lewat</small>' : '');
            return ['id' => (int) $row['id'], 'quotation_no' => '<strong>' . esc($row['quotation_no']) . '</strong>', 'company_name' => esc($row['company_name'] ?: '-'), 'title' => esc($row['title']), 'grand_total' => 'Rp ' . number_format((float) $row['grand_total'], 0, ',', '.'), 'status' => $statusLabel, 'created_at' => esc($row['created_at'] ?? '-'), 'actions' => $actions];
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
        $quotationNo = $this->model->nextQuotationNumber((int) $this->request->getPost('company_id'), $issueDate);
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
        return redirect()->to('/quotations/' . public_id($quotationId))->with('message', 'Penawaran berhasil dibuat.');
    }

    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->model);
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

    public function update(string $id)
    {
        $id = $this->resolveId($id, $this->model);
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
        return redirect()->to('/quotations/' . public_id($id))->with('message', 'Penawaran berhasil diperbarui.');
    }

    public function changeStatus(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $status = strtolower(trim((string) $this->request->getPost('status')));
        $allowed = ['sent', 'approved', 'rejected'];
        if (! in_array($status, $allowed, true)) {
            return redirect()->back()->with('errors', ['status' => 'Status tujuan tidak valid.']);
        }

        try {
            $quotation = $this->model->find($id);
            if (! $quotation) return redirect()->back()->with('errors', ['status' => 'Quotation tidak ditemukan.']);
            if (in_array($status, ['sent', 'approved'], true) && ! empty($quotation['valid_until']) && $quotation['valid_until'] < date('Y-m-d')) {
                return redirect()->back()->with('errors', ['validity' => 'Quotation belum dapat dikirim atau disetujui karena masa berlaku sudah lewat. Perpanjang masa berlaku terlebih dahulu.']);
            }
            $changedBy = (string) (session()->get('username') ?: 'system');
            $this->model->changeStatus($id, $status, $changedBy, 'Diubah melalui tombol aksi quotation.');
            return redirect()->to('/quotations')->with('message', 'Status quotation berhasil diubah menjadi ' . $status . '.');
        } catch (\InvalidArgumentException | \RuntimeException $exception) {
            return redirect()->back()->with('errors', ['status' => $exception->getMessage()]);
        }
    }

    public function extendValidity(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $quotation = $this->model->find($id);
        if (! $quotation) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (! in_array($quotation['status'], ['draft', 'sent', 'negotiation', 'expired'], true)) {
            return redirect()->back()->with('errors', ['validity' => 'Quotation final atau ditolak tidak dapat diperpanjang.']);
        }
        $days = (int) $this->request->getPost('validity_days');
        if ($days < 1 || $days > 3650) return redirect()->back()->with('errors', ['validity' => 'Masa berlaku harus antara 1 sampai 3650 hari.']);
        $today = date('Y-m-d');
        $baseDate = (! empty($quotation['valid_until']) && $quotation['valid_until'] > $today) ? $quotation['valid_until'] : $today;
        $newValidUntil = date('Y-m-d', strtotime($baseDate . ' +' . $days . ' days'));
        $issueDate = $quotation['issue_date'] ?: $today;
        $totalDays = max(1, (int) ((strtotime($newValidUntil) - strtotime($issueDate)) / 86400));
        $user = (string) (session()->get('username') ?: 'system');
        $db = db_connect(); $db->transStart();
        $this->model->update($id, ['valid_until' => $newValidUntil, 'validity_days' => $totalDays]);
        (new QuotationStatusLogModel())->insert(['quotation_id' => $id, 'from_status' => $quotation['status'], 'to_status' => $quotation['status'], 'changed_by' => $user, 'reason' => 'Masa berlaku diperpanjang ' . $days . ' hari sampai ' . $newValidUntil . '.', 'created_at' => date('Y-m-d H:i:s')]);
        if ($quotation['status'] === 'expired') {
            $this->model->changeStatus($id, 'sent', $user, 'Quotation dibuka kembali setelah masa berlaku diperpanjang.');
        }
        $db->transComplete();
        if ($db->transStatus() === false) return redirect()->back()->with('errors', ['validity' => 'Perpanjangan masa berlaku gagal disimpan.']);
        return redirect()->to('/quotations/' . public_id($id))->with('message', 'Masa berlaku diperpanjang sampai ' . $newValidUntil . '.');
    }

    public function proposeNegotiation(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $quotation = $this->model->detail($id);
        if (! $quotation) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (! in_array($quotation['status'], ['draft', 'sent', 'negotiation'], true)) return redirect()->back()->with('errors', ['status' => 'Quotation sudah final dan tidak dapat dinegosiasikan lagi.']);
        $negotiations = new QuotationNegotiationModel();
        if ($negotiations->latestPending($id)) return redirect()->back()->with('errors', ['negotiation' => 'Masih ada negosiasi yang menunggu keputusan.']);
        $latest = $negotiations->selectMax('round_no')->where('quotation_id', $id)->first();
        $round = (int) ($latest['round_no'] ?? 0) + 1;
        $snapshot = ['payment_terms' => $quotation['payment_terms'], 'delivery_terms' => $quotation['delivery_terms'], 'notes' => $quotation['notes'], 'tax_percent' => (float) $quotation['tax_percent'], 'items' => $quotation['items']];
        $user = (string) (session()->get('username') ?: 'system');
        $negotiations->insert(['quotation_id' => $id, 'round_no' => $round, 'status' => 'pending', 'proposed_by' => $user, 'customer_message' => trim((string) $this->request->getPost('customer_message')) ?: null, 'internal_notes' => trim((string) $this->request->getPost('internal_notes')) ?: null, 'snapshot_json' => json_encode($snapshot, JSON_UNESCAPED_UNICODE), 'subtotal' => $quotation['subtotal'], 'tax_amount' => $quotation['tax_amount'], 'grand_total' => $quotation['grand_total'], 'created_at' => date('Y-m-d H:i:s')]);
        if ($quotation['status'] !== 'negotiation') $this->model->changeStatus($id, 'negotiation', $user, 'Negosiasi putaran ' . $round . ' diajukan.');
        return redirect()->to('/quotations/' . public_id($id))->with('message', 'Negosiasi putaran ' . $round . ' berhasil disimpan.');
    }

    public function respondNegotiation(string $id, string $negotiationId, string $decision)
    {
        $id = $this->resolveId($id, $this->model);
        $negotiationId = $this->resolveId($negotiationId, new QuotationNegotiationModel());
        $quotation = $this->model->find($id); $negotiations = new QuotationNegotiationModel();
        $negotiation = $negotiations->where(['id' => $negotiationId, 'quotation_id' => $id])->first();
        if (! $quotation || ! $negotiation || $negotiation['status'] !== 'pending') return redirect()->back()->with('errors', ['negotiation' => 'Negosiasi tidak ditemukan atau sudah diproses.']);
        $decision = strtolower($decision);
        if (! in_array($decision, ['accepted', 'rejected'], true)) return redirect()->back()->with('errors', ['negotiation' => 'Keputusan negosiasi tidak valid.']);
        $user = (string) (session()->get('username') ?: 'system'); $now = date('Y-m-d H:i:s'); $db = db_connect(); $db->transStart();
        $negotiations->update($negotiationId, ['status' => $decision, 'responded_at' => $now, 'responded_by' => $user]);
        if ($decision === 'accepted') {
            $snapshot = json_decode((string) $negotiation['snapshot_json'], true) ?: [];
            $this->model->update($id, ['status' => 'approved', 'payment_terms' => $snapshot['payment_terms'] ?? $quotation['payment_terms'], 'delivery_terms' => $snapshot['delivery_terms'] ?? $quotation['delivery_terms'], 'notes' => $snapshot['notes'] ?? $quotation['notes'], 'subtotal' => $negotiation['subtotal'], 'tax_percent' => $snapshot['tax_percent'] ?? $quotation['tax_percent'], 'tax_amount' => $negotiation['tax_amount'], 'grand_total' => $negotiation['grand_total']]);
            if (! empty($snapshot['items'])) { (new QuotationItemModel())->where('quotation_id', $id)->delete(); (new QuotationItemModel())->insertBatch($snapshot['items']); }
            (new QuotationStatusLogModel())->insert(['quotation_id' => $id, 'from_status' => $quotation['status'], 'to_status' => 'approved', 'changed_by' => $user, 'reason' => 'Negosiasi putaran ' . $negotiation['round_no'] . ' diterima; menjadi kondisi final.', 'created_at' => $now]);
        } else { $this->model->changeStatus($id, 'sent', $user, 'Negosiasi putaran ' . $negotiation['round_no'] . ' ditolak.'); }
        $db->transComplete();
        if ($db->transStatus() === false) return redirect()->back()->with('errors', ['negotiation' => 'Keputusan negosiasi gagal disimpan.']);
        return redirect()->to('/quotations/' . public_id($id))->with('message', 'Negosiasi berhasil ' . ($decision === 'accepted' ? 'diterima sebagai final.' : 'ditolak.') );
    }

    public function proformaInvoice(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $quotation = $this->model->detail($id);
        if (! $quotation) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if ($quotation['status'] !== 'approved') return redirect()->to('/quotations/' . public_id($id))->with('errors', ['status' => 'Proforma Invoice hanya dapat dicetak setelah quotation final disetujui.']);
        $settings = (new QuotationSettingModel())->current(); $options = new Options(); $options->set('isRemoteEnabled', true); $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('quotations/proforma_invoice', ['quotation' => $quotation, 'settings' => $settings])); $dompdf->setPaper('A4', 'portrait'); $dompdf->render();
        return $this->response->setHeader('Content-Type', 'application/pdf')->setHeader('Content-Disposition', 'attachment; filename="proforma-invoice-' . $quotation['quotation_no'] . '.pdf"')->setBody($dompdf->output());
    }

    public function show(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $this->model->expireOverdue();
        $quotation = $this->model->detail($id);
        if (! $quotation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('quotations/show', ['title' => 'Detail Penawaran', 'quotation' => $quotation]);
    }

    public function pdf(string $id)
    {
        $id = $this->resolveId($id, $this->model);
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

    public function catalogPreview(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $this->model->expireOverdue();
        $context = $this->catalogContext($id);
        return view('quotations/catalog_preview', $context);
    }

    public function catalog(string $id)
    {
        $id = $this->resolveId($id, $this->model);
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

    public function delete(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $this->model->delete($id);
        return redirect()->to('/quotations')->with('message', 'Penawaran berhasil dihapus.');
    }
}
