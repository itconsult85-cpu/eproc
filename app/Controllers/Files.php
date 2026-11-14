<?php

namespace App\Controllers;

use App\Libraries\SecureFileStorage;
use App\Models\ProformaInvoiceModel;
use App\Models\ProductModel;
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
        return $this->inline($row['file_path'] ?? null, $row['original_file_name'] ?? 'tender-document', $row['file_mime'] ?? null);
    }

    public function proformaProof(string $publicId)
    {
        $model = new ProformaInvoiceModel();
        $id = $this->resolveId($publicId, $model);
        $row = $model->find($id);
        $quotation = $row ? (new \App\Models\QuotationModel())->find((int) $row['quotation_id']) : null;
        if (! $row || ! $quotation || ! can_access_company((int) $quotation['company_id'])) throw PageNotFoundException::forPageNotFound();
        return $this->download($row['proof_path'] ?? null, 'proforma-proof');
    }

    public function vendorBillProof(string $publicId)
    {
        $model = new VendorBillModel();
        $id = $this->resolveId($publicId, $model);
        $row = $model->accessible($id);
        if (! $row) throw PageNotFoundException::forPageNotFound();
        return $this->download($row['proof_path'] ?? null, 'vendor-bill-proof');
    }

    public function productDatasheet(string $publicId)
    {
        $model = new ProductModel();
        $id = $this->resolveId($publicId, $model);
        $row = $model->applyCompanyScope(allowed_company_ids())->find($id);
        if (! $row) throw PageNotFoundException::forPageNotFound();
        return $this->download($row['datasheet_file_path'] ?? null, 'product-datasheet-' . $publicId . '.pdf');
    }

    private function download(?string $storedPath, string $downloadName)
    {
        if (! $storedPath || ($path = SecureFileStorage::resolve($storedPath)) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $downloadName = preg_replace('/[^A-Za-z0-9._-]/', '-', basename($downloadName)) ?: 'download';
        return $this->response->download($path, null)->setFileName($downloadName);
    }

    private function inline(?string $storedPath, string $fileName, ?string $mimeType = null)
    {
        if (! $storedPath || ($path = SecureFileStorage::resolve($storedPath)) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $fileName = preg_replace('/[^A-Za-z0-9._-]/', '-', basename($fileName)) ?: 'document';
        $mimeType = mime_content_type($path) ?: ($mimeType ?: 'application/octet-stream');

        return $this->response
            ->setContentType($mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $fileName . '"')
            ->setHeader('Content-Length', (string) filesize($path))
            ->setBody((string) file_get_contents($path));
    }
}