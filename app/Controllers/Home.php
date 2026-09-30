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
        $vendorBills = new VendorBillModel();

        return view('dashboard', [
            'title' => 'Dashboard',
            'companyCount' => (new CompanyModel())->countAllResults(),
            'productCount' => (new ProductModel())->countAllResults(),
            'quotationCount' => $quotations->countAllResults(),
            'draftQuotationCount' => (clone $quotations)->where('status', 'draft')->countAllResults(),
            'negotiationQuotationCount' => (clone $quotations)->where('status', 'negotiation')->countAllResults(),
            'approvedQuotationCount' => (clone $quotations)->where('status', 'approved')->countAllResults(),
            'vendorCount' => (new VendorModel())->countAllResults(),
            'clientPurchaseOrderCount' => (new ClientPurchaseOrderModel())->countAllResults(),
            'purchaseOrderCount' => (new PurchaseOrderModel())->countAllResults(),
            'proformaCount' => (new ProformaInvoiceModel())->countAllResults(),
            'unpaidBillCount' => (clone $vendorBills)->whereIn('status', ['unpaid', 'partial'])->countAllResults(),
            'bastCount' => (new BastModel())->countAllResults(),
            'deliveryNoteCount' => (new ClientDeliveryNoteModel())->countAllResults(),
            'recentQuotations' => (clone $quotations)->orderBy('created_at', 'DESC')->findAll(5),
        ]);
    }
}
