<?php

namespace App\Models;

class VendorBillModel extends BaseModel
{
    protected $table = 'vendor_bills';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'vendor_id',
        'purchase_order_id',
        'bill_no',
        'bill_date',
        'due_date',
        'amount',
        'use_ppn',
        'ppn_percent',
        'ppn_amount',
        'use_pph',
        'pph_percent',
        'pph_amount',
        'net_amount',
        'status',
        'paid_date',
        'payment_reference',
        'proof_path',
        'notes',
        'created_at',
        'updated_at'
    ];
    public function withVendor(): array
    {
        $builder = $this->select('vendor_bills.*, vendors.name AS vendor_name, purchase_orders.po_no')->join('vendors', 'vendors.id=vendor_bills.vendor_id')->join('purchase_orders', 'purchase_orders.id=vendor_bills.purchase_order_id', 'left')->join('client_purchase_orders', 'client_purchase_orders.id=purchase_orders.client_purchase_order_id', 'left');
        $this->applyCompanyScope($builder);
        return $builder->orderBy('vendor_bills.created_at', 'DESC')->findAll();
    }

    public function accessible(int $id): ?array
    {
        $builder = $this->select('vendor_bills.*, vendors.name AS vendor_name, purchase_orders.po_no')->join('vendors', 'vendors.id=vendor_bills.vendor_id')->join('purchase_orders', 'purchase_orders.id=vendor_bills.purchase_order_id', 'left')->join('client_purchase_orders', 'client_purchase_orders.id=purchase_orders.client_purchase_order_id', 'left');
        $this->applyCompanyScope($builder);
        return $builder->find($id);
    }

    private function applyCompanyScope($builder): void
    {
        $companyIds = allowed_company_ids();
        if ($companyIds === null) return;
        $builder->groupStart()->where('client_purchase_orders.company_id IS NULL', null, false);
        if ($companyIds) $builder->orWhereIn('client_purchase_orders.company_id', $companyIds);
        $builder->groupEnd();
    }
}
