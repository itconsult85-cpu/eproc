<?php

namespace App\Models;

class ClientPurchaseOrderModel extends BaseModel
{
    protected $table = 'client_purchase_orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'quotation_id',
        'company_id',
        'po_no',
        'po_date',
        'status',
        'subtotal',
        'tax_percent',
        'tax_amount',
        'grand_total',
        'notes',
        'created_at',
        'updated_at'
    ];

    public function withQuotation(): array
    {
        return $this->select('client_purchase_orders.*, quotations.quotation_no, quotations.title, companies.name AS company_name')
            ->join('quotations', 'quotations.id = client_purchase_orders.quotation_id')
            ->join('companies', 'companies.id = client_purchase_orders.company_id', 'left')
            ->orderBy('client_purchase_orders.created_at', 'DESC')->findAll();
    }

    public function detail(int $id): ?array
    {
        $row = $this->select('client_purchase_orders.*, quotations.quotation_no, quotations.title, quotations.customer_name, quotations.customer_address, companies.name AS company_name, companies.address AS company_address, companies.phone AS company_phone')
            ->join('quotations', 'quotations.id = client_purchase_orders.quotation_id')
            ->join('companies', 'companies.id = client_purchase_orders.company_id', 'left')->find($id);
        if (! $row) return null;
        $row['items'] = (new ClientPurchaseOrderItemModel())->where('client_purchase_order_id', $id)->findAll();
        return $row;
    }

    public function listPage(string $search, int $start, int $length, string $orderColumn = 'created_at', string $direction = 'DESC'): array
    {
        $allowed = ['po_no' => 'client_purchase_orders.po_no', 'quotation_no' => 'quotations.quotation_no', 'company' => 'companies.name', 'po_date' => 'client_purchase_orders.po_date', 'grand_total' => 'client_purchase_orders.grand_total', 'status' => 'client_purchase_orders.status', 'created_at' => 'client_purchase_orders.created_at'];
        $builder = $this->select('client_purchase_orders.*, quotations.quotation_no, quotations.title, companies.name AS company_name')
            ->join('quotations', 'quotations.id = client_purchase_orders.quotation_id')
            ->join('companies', 'companies.id = client_purchase_orders.company_id', 'left');
        if ($search !== '') $builder->groupStart()->like('client_purchase_orders.po_no', $search)->orLike('quotations.quotation_no', $search)->orLike('quotations.title', $search)->orLike('companies.name', $search)->groupEnd();
        return $builder->orderBy($allowed[$orderColumn] ?? $allowed['created_at'], $direction === 'ASC' ? 'ASC' : 'DESC')->findAll(max(1, min($length, 100)), max(0, $start));
    }

    public function countFiltered(string $search): int
    {
        $builder = $this->join('quotations', 'quotations.id = client_purchase_orders.quotation_id')->join('companies', 'companies.id = client_purchase_orders.company_id', 'left');
        if ($search !== '') $builder->groupStart()->like('client_purchase_orders.po_no', $search)->orLike('quotations.quotation_no', $search)->orLike('quotations.title', $search)->orLike('companies.name', $search)->groupEnd();
        return $builder->countAllResults();
    }
}
