<?php

namespace App\Controllers;

use App\Libraries\SecureFileStorage;
use App\Models\ProformaInvoiceModel;
use App\Models\TenderDocumentModel;
use App\Models\VendorBillModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Files extends BaseController
{
    public function tender(string $publicId)
    {
        $model = new TenderDocumentModel();
        $id = $this->resolveId($publicId, $model);
        $row = $model->find($id);
        return $this->download($row['file_path'] ?? null, $row['original_file_name'] ?? 'tender-document');
    }

    public function proformaProof(string $publicId)
    {
        $model = new ProformaInvoiceModel();
        $id = $this->resolveId($publicId, $model);
        $row = $model->find($id);
        return $this->download($row['proof_path'] ?? null, 'proforma-proof');
    }

    public function vendorBillProof(string $publicId)
    {
        $model = new VendorBillModel();
        $id = $this->resolveId($publicId, $model);
        $row = $model->find($id);
        return $this->download($row['proof_path'] ?? null, 'vendor-bill-proof');
    }

    private function download(?string $storedPath, string $downloadName)
    {
        if (! $storedPath || ($path = SecureFileStorage::resolve($storedPath)) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $downloadName = preg_replace('/[^A-Za-z0-9._-]/', '-', basename($downloadName)) ?: 'download';
        return $this->response->download($path, null)->setFileName($downloadName);
    }
}
