<?php

namespace App\Models;

class PurchaseOrderModel extends BaseModel
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'vendor_id',
        'client_purchase_order_id',
        'created_by',
        'po_no',
        'po_date',
        'expected_date',
        'status',
        'subtotal',
        'tax_percent',
        'use_ppn',
        'ppn_percent',
        'use_pph',
        'pph_percent',
        'tax_amount',
        'pph_amount',
        'grand_total',
        'notes',
        'created_at',
        'updated_at'
    ];

    public function withVendor(): array
    {
        $builder = $this->select('purchase_orders.*, vendors.name AS vendor_name, client_purchase_orders.po_no AS client_po_no')
            ->join('vendors', 'vendors.id = purchase_orders.vendor_id')
            ->join('client_purchase_orders', 'client_purchase_orders.id = purchase_orders.client_purchase_order_id', 'left');
        $this->applyCompanyScope($builder);
        return $builder->orderBy('purchase_orders.created_at', 'DESC')->findAll();
    }

    public function detail(int $id): ?array
    {
        $builder = $this->select('purchase_orders.*, vendors.name AS vendor_name, client_purchase_orders.po_no AS client_po_no, users.full_name AS created_by_name, users.signature_path AS created_by_signature_path')
            ->join('vendors', 'vendors.id = purchase_orders.vendor_id')
            ->join('client_purchase_orders', 'client_purchase_orders.id = purchase_orders.client_purchase_order_id', 'left')
            ->join('users', 'users.id = purchase_orders.created_by', 'left');
        $this->applyCompanyScope($builder);
        $row = $builder->find($id);
        if (! $row) return null;
        $row['items'] = (new PurchaseOrderItemModel())->where('purchase_order_id', $id)->findAll();
        return $row;
    }

    public function listPage(string $search, int $start, int $length, string $orderColumn = 'created_at', string $direction = 'DESC'): array
    {
        $allowed = ['po_no' => 'purchase_orders.po_no', 'client_po' => 'client_purchase_orders.po_no', 'vendor' => 'vendors.name', 'date' => 'purchase_orders.po_date', 'amount' => 'purchase_orders.grand_total', 'status' => 'purchase_orders.status'];
        $builder = $this->select('purchase_orders.*, vendors.name AS vendor_name, client_purchase_orders.po_no AS client_po_no')->join('vendors', 'vendors.id = purchase_orders.vendor_id')->join('client_purchase_orders', 'client_purchase_orders.id = purchase_orders.client_purchase_order_id', 'left');
        $this->applyCompanyScope($builder);
        if ($search !== '') $builder->groupStart()->like('purchase_orders.po_no', $search)->orLike('client_purchase_orders.po_no', $search)->orLike('vendors.name', $search)->groupEnd();
        return $builder->orderBy($allowed[$orderColumn] ?? 'purchase_orders.created_at', $direction === 'ASC' ? 'ASC' : 'DESC')->findAll(max(1, min($length, 100)), max(0, $start));
    }

    public function countFiltered(string $search): int
    {
        $builder = $this->join('vendors', 'vendors.id = purchase_orders.vendor_id')->join('client_purchase_orders', 'client_purchase_orders.id = purchase_orders.client_purchase_order_id', 'left');
        $this->applyCompanyScope($builder);
        if ($search !== '') $builder->groupStart()->like('purchase_orders.po_no', $search)->orLike('client_purchase_orders.po_no', $search)->orLike('vendors.name', $search)->groupEnd();
        return $builder->countAllResults();
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
