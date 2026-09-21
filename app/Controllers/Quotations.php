<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProductModel;
use App\Models\QuotationItemModel;
use App\Models\QuotationModel;
use App\Models\QuotationSettingModel;
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
        return view('quotations/index', ['title' => 'Penawaran', 'quotations' => $this->model->withCompany()]);
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
        $rules = ['company_id' => 'required|is_natural_no_zero', 'title' => 'required|max_length[220]', 'quotation_no' => 'required|max_length[60]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
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
        $validityDays = max(0, (int) ($this->request->getPost('validity_days') ?: $settings['default_validity_days'] ?? 10));
        $validUntil = date('Y-m-d', strtotime($issueDate . ' +' . $validityDays . ' days'));
        $taxPercent = max(0, (float) ($this->request->getPost('tax_percent') ?: $settings['default_tax_percent'] ?? 0));
        $tax = $subtotal * $taxPercent / 100;
        $quotationId = $this->model->insert([
            'company_id' => $this->request->getPost('company_id'),
            'quotation_no' => $this->request->getPost('quotation_no'),
            'customer_name' => $this->request->getPost('customer_name'),
            'customer_address' => $this->request->getPost('customer_address'),
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
        if ($items) {
            foreach ($items as &$item) {
                $item['quotation_id'] = $quotationId;
            }
            (new QuotationItemModel())->insertBatch($items);
        }
        return redirect()->to('/quotations/' . $quotationId)->with('message', 'Penawaran berhasil dibuat.');
    }

    public function show(int $id)
    {
        $quotation = $this->model->detail($id);
        if (! $quotation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('quotations/show', ['title' => 'Detail Penawaran', 'quotation' => $quotation]);
    }

    public function pdf(int $id)
    {
        $quotation = $this->model->detail($id);
        if (! $quotation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $settings = (new QuotationSettingModel())->current();
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('quotations/pdf', ['quotation' => $quotation, 'settings' => $settings, 'logoData' => $this->assetData($settings['logo_path'] ?? null), 'signatureData' => $this->assetData($settings['signature_path'] ?? null), 'stampData' => $this->assetData($settings['stamp_path'] ?? null)]));
        $dompdf->setPaper('letter');
        $dompdf->render();
        return $this->response->setHeader('Content-Type', 'application/pdf')->setHeader('Content-Disposition', 'attachment; filename="quotation-' . $quotation['quotation_no'] . '.pdf"')->setBody($dompdf->output());
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
