<?php

namespace App\Controllers;

use App\Models\QuotationSettingModel;
use App\Models\CompanyBankAccountModel;

class Settings extends BaseController
{
    private QuotationSettingModel $model;

    public function __construct()
    {
        $this->model = new QuotationSettingModel();
    }

    public function quotation()
    {
        return view('settings/quotation', ['title' => 'Setting Quotation', 'settings' => $this->model->current(), 'bankAccounts' => (new CompanyBankAccountModel())->orderBy('is_default', 'DESC')->orderBy('bank_name')->findAll()]);
    }

    public function saveBankAccount(?string $id = null)
    {
        $accounts = new CompanyBankAccountModel();
        $id = $id ? $this->resolveId($id, $accounts) : null;
        $data = $this->request->getPost(['bank_name', 'account_name', 'account_number', 'branch', 'currency', 'notes']);
        $data['is_active'] = $this->request->getPost('is_active') ? 1 : 0;
        $data['is_default'] = $this->request->getPost('is_default') ? 1 : 0;
        if (! $data['bank_name'] || ! $data['account_name'] || ! $data['account_number']) return redirect()->back()->withInput()->with('errors', ['bank' => 'Bank, nama pemilik, dan nomor rekening wajib diisi.']);
        if ($data['is_default']) $accounts->where('id !=', (int) ($id ?? 0))->set(['is_default' => 0])->update();
        $id ? $accounts->update($id, $data) : $accounts->insert($data);
        return redirect()->to('/settings/quotation')->with('message', 'Rekening perusahaan berhasil disimpan.');
    }

    public function deleteBankAccount(string $id)
    {
        $accounts = new CompanyBankAccountModel();
        $accounts->delete($this->resolveId($id, $accounts));
        return redirect()->to('/settings/quotation')->with('message', 'Rekening perusahaan berhasil dihapus.');
    }

    public function saveQuotation()
    {
        $data = $this->request->getPost(['company_name', 'office_1', 'office_2', 'phone', 'email', 'tax_id', 'bank_name', 'bank_account_name', 'bank_account_number', 'bank_branch', 'signer_name', 'signer_phone', 'default_payment_terms', 'default_validity_days', 'default_delivery_terms', 'default_tax_percent']);
        if (! $this->validateData($data, ['company_name' => 'required|max_length[180]', 'email' => 'permit_empty|valid_email', 'default_validity_days' => 'required|is_natural', 'default_tax_percent' => 'required|decimal'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $existingRow = $this->model->find(1);
        $existing = $existingRow ?? $this->model->current();
        foreach (['logo' => ['logo_path', ['jpg', 'jpeg', 'png', 'webp'], 5242880], 'signature' => ['signature_path', ['jpg', 'jpeg', 'png', 'webp'], 5242880], 'stamp' => ['stamp_path', ['jpg', 'jpeg', 'png', 'webp'], 5242880]] as $field => [$column, $extensions, $maxSize]) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                if ($file->getSize() > $maxSize || ! in_array(strtolower($file->getExtension()), $extensions, true)) {
                    return redirect()->back()->withInput()->with('errors', [$field => 'File ' . $field . ' harus JPG, PNG, atau WEBP maksimal 5 MB.']);
                }
                $directory = FCPATH . 'uploads/settings';
                if (! is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                $newName = $file->getRandomName();
                $file->move($directory, $newName);
                $data[$column] = '/uploads/settings/' . $newName;
            } elseif (! empty($existing[$column])) {
                $data[$column] = $existing[$column];
            }
        }
        $payload = array_merge(['id' => 1], $data);
        $saved = $existingRow
            ? $this->model->update(1, $data)
            : $this->model->insert($payload);
        if ($saved === false) {
            $dbError = db_connect()->error()['message'] ?? 'Kesalahan database tidak diketahui.';
            return redirect()->back()->withInput()->with('errors', ['settings' => 'Setting quotation gagal disimpan: ' . $dbError]);
        }
        return redirect()->to('/settings/quotation')->with('message', 'Setting quotation berhasil disimpan.');
    }
}
