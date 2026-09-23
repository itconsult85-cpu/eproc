<?php

namespace App\Controllers;

use App\Models\QuotationSettingModel;

class Settings extends BaseController
{
    private QuotationSettingModel $model;

    public function __construct()
    {
        $this->model = new QuotationSettingModel();
    }

    public function quotation()
    {
        return view('settings/quotation', ['title' => 'Setting Quotation', 'settings' => $this->model->current()]);
    }

    public function saveQuotation()
    {
        $data = $this->request->getPost(['company_name', 'office_1', 'office_2', 'phone', 'email', 'tax_id', 'signer_name', 'signer_phone', 'default_payment_terms', 'default_validity_days', 'default_delivery_terms', 'default_tax_percent']);
        if (! $this->validateData($data, ['company_name' => 'required|max_length[180]', 'email' => 'permit_empty|valid_email', 'default_validity_days' => 'required|is_natural', 'default_tax_percent' => 'required|decimal'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $existing = $this->model->current();
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
        $this->model->save(array_merge(['id' => 1], $data));
        return redirect()->to('/settings/quotation')->with('message', 'Setting quotation berhasil disimpan.');
    }
}