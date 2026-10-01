<?php

namespace App\Controllers;

use App\Models\BastModel;
use App\Models\ClientDeliveryNoteModel;
use App\Models\ClientPurchaseOrderModel;
use App\Models\CompanyModel;
use App\Models\ProductModel;
use App\Models\ProformaInvoiceModel;
use App\Models\PurchaseOrderModel;
use App\Models\QuotationModel;
use App\Models\VendorBillModel;
use App\Models\VendorModel;

class Home extends BaseController
{
    public function index(): string
    {
        $quotations = new QuotationModel();

        return view('dashboard', [
            'title' => 'Dashboard',
            'companyCount' => $this->safeMetric(fn() => (new CompanyModel())->countAllResults()),
            'productCount' => $this->safeMetric(fn() => (new ProductModel())->countAllResults()),
            'quotationCount' => $this->safeMetric(fn() => (new QuotationModel())->countAllResults()),
            'draftQuotationCount' => $this->safeMetric(fn() => (new QuotationModel())->where('status', 'draft')->countAllResults()),
            'negotiationQuotationCount' => $this->safeMetric(fn() => (new QuotationModel())->where('status', 'negotiation')->countAllResults()),
            'approvedQuotationCount' => $this->safeMetric(fn() => (new QuotationModel())->where('status', 'approved')->countAllResults()),
            'vendorCount' => $this->safeMetric(fn() => (new VendorModel())->countAllResults()),
            'clientPurchaseOrderCount' => $this->safeMetric(fn() => (new ClientPurchaseOrderModel())->countAllResults()),
            'purchaseOrderCount' => $this->safeMetric(fn() => (new PurchaseOrderModel())->countAllResults()),
            'proformaCount' => $this->safeMetric(fn() => (new ProformaInvoiceModel())->countAllResults()),
            'unpaidBillCount' => $this->safeMetric(fn() => (new VendorBillModel())->whereIn('status', ['unpaid', 'partial'])->countAllResults()),
            'bastCount' => $this->safeMetric(fn() => (new BastModel())->countAllResults()),
            'deliveryNoteCount' => $this->safeMetric(fn() => (new ClientDeliveryNoteModel())->countAllResults()),
            'recentQuotations' => $this->safeList(fn() => $quotations->orderBy('created_at', 'DESC')->findAll(5)),
        ]);
    }

    private function safeMetric(callable $query): int
    {
        try {
            return max(0, (int) $query());
        } catch (\Throwable $exception) {
            log_message('warning', 'Dashboard metric unavailable: {message}', ['message' => $exception->getMessage()]);
            return 0;
        }
    }

    private function safeList(callable $query): array
    {
        try {
            return (array) $query();
        } catch (\Throwable $exception) {
            log_message('warning', 'Dashboard list unavailable: {message}', ['message' => $exception->getMessage()]);
            return [];
        }
    }
}
