<?php

namespace App\Controllers;

use App\Libraries\SecureFileStorage;
use App\Models\ProformaInvoiceModel;
use App\Models\QuotationModel;
use RuntimeException;

class ProformaInvoices extends BaseController
{
    private ProformaInvoiceModel $model;

    public function __construct()
    {
        $this->model = new ProformaInvoiceModel();
    }

    public function index()
    {
        return view('proforma/index', ['title' => 'Proforma Invoice']);
    }

    public function datatable()
    {
        $request = $this->request->getGet();
        $query = trim((string) ($request['search']['value'] ?? ''));
        $rows = $this->model->withQuotation();
        if ($query !== '') {
            $rows = array_values(array_filter($rows, static fn(array $row): bool => stripos($row['invoice_no'] . ' ' . $row['quotation_no'] . ' ' . $row['company_name'], $query) !== false));
        }
        $data = array_map(static function (array $row): array {
            $id = public_id((int) $row['id']);
            $status = ['paid' => 'success', 'partial' => 'warning', 'unpaid' => 'danger'][$row['payment_status']] ?? 'secondary';
            $actions = '<div class="dropdown datatable-actions"><button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Aksi</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="/proforma-invoices/' . $id . '/edit"><i class="bi bi-cash-coin me-2"></i>Kelola pembayaran</a></li><li><a class="dropdown-item" href="/quotations/' . public_id((int) $row['quotation_id']) . '/proforma-invoice"><i class="bi bi-file-earmark-pdf me-2"></i>Unduh PDF</a></li></ul></div>';
            return [
                'invoice_no' => '<strong>' . esc($row['invoice_no']) . '</strong><div class="small text-body-secondary">' . esc($row['quotation_no']) . '</div>',
                'customer' => esc($row['company_name']),
                'amount' => 'Rp ' . number_format((float) $row['amount'], 0, ',', '.'),
                'due' => esc($row['due_date'] ?? '-'),
                'status' => '<span class="badge text-bg-' . $status . '">' . esc(ucfirst($row['payment_status'])) . '</span>',
                'actions' => $actions,
            ];
        }, $rows);
        return $this->response->setJSON(['draw' => (int) ($request['draw'] ?? 0), 'recordsTotal' => count($rows), 'recordsFiltered' => count($rows), 'data' => $data]);
    }

    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $invoice = $this->model->find($id);
        if (! $invoice) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('proforma/form', ['title' => 'Kelola Pembayaran Proforma', 'invoice' => $invoice, 'action' => '/proforma-invoices/' . public_id($id)]);
    }

    public function update(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $invoice = $this->model->find($id);
        if (! $invoice) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $data = $this->request->getPost(['payment_status', 'payment_date', 'payment_method', 'payment_reference', 'notes']);
        if (! in_array($data['payment_status'] ?? '', ['unpaid', 'partial', 'paid'], true)) {
            return redirect()->back()->withInput()->with('error', 'Status pembayaran tidak valid.');
        }

        $file = $this->request->getFile('proof');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            try {
                $stored = SecureFileStorage::store($file, [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'webp' => 'image/webp',
                    'pdf' => 'application/pdf',
                ], 5242880, 'payment-proofs');
            } catch (RuntimeException $exception) {
                return redirect()->back()->withInput()->with('error', 'Bukti bayar harus JPG, PNG, WEBP, atau PDF maksimal 5 MB dan tidak boleh dipalsukan.');
            }
            $data['proof_path'] = $stored['path'];
            SecureFileStorage::remove($invoice['proof_path'] ?? null);
        }
        if (($data['payment_status'] ?? '') === 'paid' && empty($data['payment_date'])) $data['payment_date'] = date('Y-m-d');
        $this->model->update($id, $data);
        return redirect()->to('/proforma-invoices')->with('message', 'Status pembayaran proforma berhasil diperbarui.');
    }

    public function syncApproved()
    {
        foreach ((new QuotationModel())->where('status', 'approved')->findAll() as $quotation) {
            if (! $this->model->where('quotation_id', $quotation['id'])->first()) {
                $this->model->insert(['quotation_id' => $quotation['id'], 'invoice_no' => 'PI-' . $quotation['quotation_no'], 'invoice_date' => date('Y-m-d'), 'due_date' => date('Y-m-d', strtotime('+30 days')), 'amount' => $quotation['grand_total'], 'payment_status' => 'unpaid', 'created_at' => date('Y-m-d H:i:s')]);
            }
        }
        return redirect()->to('/proforma-invoices');
    }
}
