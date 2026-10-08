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
        $allowed = allowed_company_ids();

        return view('dashboard', [
            'title' => 'Dashboard',
            'companyCount' => $this->safeMetric(fn() => $this->countScoped((new CompanyModel())->builder(), 'id')),
            'productCount' => $this->safeMetric(fn() => (new ProductModel())->applyCompanyScope($allowed)->countAllResults()),
            'quotationCount' => $this->safeMetric(fn() => $this->countScoped((new QuotationModel())->builder(), 'company_id')),
            'draftQuotationCount' => $this->safeMetric(fn() => $this->countScoped((new QuotationModel())->where('status', 'draft')->builder(), 'company_id')),
            'negotiationQuotationCount' => $this->safeMetric(fn() => $this->countScoped((new QuotationModel())->where('status', 'negotiation')->builder(), 'company_id')),
            'approvedQuotationCount' => $this->safeMetric(fn() => $this->countScoped((new QuotationModel())->where('status', 'approved')->builder(), 'company_id')),
            'vendorCount' => $this->safeMetric(fn() => (new VendorModel())->countAllResults()),
            'clientPurchaseOrderCount' => $this->safeMetric(fn() => $this->countScoped((new ClientPurchaseOrderModel())->builder(), 'company_id')),
            'purchaseOrderCount' => $this->safeMetric(fn() => $this->countScoped((new PurchaseOrderModel())->builder()->join('client_purchase_orders', 'client_purchase_orders.id = purchase_orders.client_purchase_order_id', 'left'), 'client_purchase_orders.company_id')),
            'proformaCount' => $this->safeMetric(fn() => $this->countScoped((new ProformaInvoiceModel())->builder()->join('quotations', 'quotations.id = proforma_invoices.quotation_id'), 'quotations.company_id')),
            'unpaidBillCount' => $this->safeMetric(fn() => $this->countScoped((new VendorBillModel())->whereIn('status', ['unpaid', 'partial'])->builder()->join('purchase_orders', 'purchase_orders.id = vendor_bills.purchase_order_id', 'left')->join('client_purchase_orders', 'client_purchase_orders.id = purchase_orders.client_purchase_order_id', 'left'), 'client_purchase_orders.company_id')),
            'bastCount' => $this->safeMetric(fn() => $this->countScoped((new BastModel())->builder(), 'company_id')),
            'deliveryNoteCount' => $this->safeMetric(fn() => $this->countScoped((new ClientDeliveryNoteModel())->builder(), 'company_id')),
            'recentQuotations' => $this->safeList(fn() => array_slice($quotations->withCompany($allowed), 0, 5)),
        ]);
    }

    private function countScoped($builder, string $companyColumn): int
    {
        $allowed = allowed_company_ids();
        if ($allowed !== null) $builder->whereIn($companyColumn, $allowed ?: [0]);
        return (int) $builder->countAllResults();
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
