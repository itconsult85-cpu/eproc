<?php

namespace App\Controllers;

use App\Libraries\SecureFileStorage;
use App\Models\VendorBillModel;
use App\Models\VendorModel;
use RuntimeException;

class VendorBills extends BaseController
{
    private VendorBillModel $model;

    public function __construct()
    {
        $this->model = new VendorBillModel();
    }

    public function index()
    {
        return view('vendor_bills/index', ['title' => 'Tagihan Vendor']);
    }

    public function datatable()
    {
        $request = $this->request->getGet();
        $rows = $this->model->withVendor();
        $data = array_map(static function (array $row): array {
            $id = public_id((int) $row['id']);
            $class = $row['status'] === 'paid' ? 'success' : ($row['status'] === 'partial' ? 'warning' : 'danger');
            $actions = '<div class="dropdown datatable-actions"><button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Aksi</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="/vendor-bills/' . $id . '/edit"><i class="bi bi-cash-coin me-2"></i>Kelola tagihan</a></li></ul></div>';
            return ['bill_no' => '<strong>' . esc($row['bill_no']) . '</strong><div class="small">PO: ' . esc($row['po_no'] ?? '-') . '</div>', 'vendor' => esc($row['vendor_name']), 'amount' => 'Rp ' . number_format((float) ($row['net_amount'] ?: $row['amount']), 0, ',', '.'), 'due' => esc($row['due_date'] ?? '-'), 'status' => '<span class="badge text-bg-' . $class . '">' . esc(ucfirst($row['status'])) . '</span>', 'actions' => $actions];
        }, $rows);
        return $this->response->setJSON(['draw' => (int) ($request['draw'] ?? 0), 'recordsTotal' => count($data), 'recordsFiltered' => count($data), 'data' => $data]);
    }

    public function new()
    {
        return view('vendor_bills/form', ['title' => 'Tambah Tagihan Vendor', 'bill' => [], 'vendors' => (new VendorModel())->where('is_active', 1)->findAll(), 'action' => '/vendor-bills']);
    }

    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $bill = $this->model->find($id);
        if (! $bill) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('vendor_bills/form', ['title' => 'Edit Tagihan Vendor', 'bill' => $bill, 'vendors' => (new VendorModel())->findAll(), 'action' => '/vendor-bills/' . public_id($id)]);
    }

    public function save(?string $id = null)
    {
        $id = $id ? $this->resolveId($id, $this->model) : null;
        $existing = $id ? $this->model->find($id) : [];
        $data = $this->request->getPost(['vendor_id', 'purchase_order_id', 'bill_no', 'bill_date', 'due_date', 'amount', 'status', 'paid_date', 'payment_reference', 'notes']);
        $amount = (float) ($data['amount'] ?? 0);
        $usePpn = $this->request->getPost('use_ppn') ? 1 : 0;
        $ppnPercent = max(0, min(100, (float) $this->request->getPost('ppn_percent')));
        $usePph = $this->request->getPost('use_pph') ? 1 : 0;
        $pphPercent = max(0, min(100, (float) $this->request->getPost('pph_percent')));
        $data += ['use_ppn' => $usePpn, 'ppn_percent' => $ppnPercent, 'ppn_amount' => $usePpn ? $amount * $ppnPercent / 100 : 0, 'use_pph' => $usePph, 'pph_percent' => $pphPercent, 'pph_amount' => $usePph ? $amount * $pphPercent / 100 : 0];
        $data['net_amount'] = $amount + $data['ppn_amount'] - $data['pph_amount'];
        if (! $data['vendor_id'] || ! $data['bill_no'] || $amount < 0 || ! in_array($data['status'] ?? '', ['unpaid', 'partial', 'paid'], true)) return redirect()->back()->withInput()->with('errors', ['bill_no' => 'Vendor, nomor tagihan, nominal, dan status harus valid.']);

        $file = $this->request->getFile('proof');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            try {
                $stored = SecureFileStorage::store($file, ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'pdf' => 'application/pdf'], 5242880, 'payment-proofs');
            } catch (RuntimeException $exception) {
                return redirect()->back()->withInput()->with('error', 'Bukti pembayaran harus JPG, PNG, WEBP, atau PDF maksimal 5 MB dan tidak boleh dipalsukan.');
            }
            $data['proof_path'] = $stored['path'];
            SecureFileStorage::remove($existing['proof_path'] ?? null);
        } elseif ($existing) {
            $data['proof_path'] = $existing['proof_path'] ?? null;
        }
        $id ? $this->model->update($id, $data) : $this->model->insert($data);
        return redirect()->to('/vendor-bills')->with('message', 'Tagihan vendor berhasil disimpan.');
    }

    public function delete(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $bill = $this->model->find($id);
        if ($bill) {
            SecureFileStorage::remove($bill['proof_path'] ?? null);
            $this->model->delete($id);
        }
        return redirect()->to('/vendor-bills')->with('message', 'Tagihan vendor dihapus.');
    }
}
