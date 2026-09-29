<?php

namespace App\Models;

class ClientDeliveryNoteModel extends BaseModel
{
    protected $table = 'client_delivery_notes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['client_purchase_order_id', 'company_id', 'delivery_no', 'delivery_date', 'destination', 'recipient_name', 'recipient_position', 'delivered_by', 'delivered_position', 'status', 'items_json', 'notes', 'created_by', 'created_at', 'updated_at'];

    public function withPurchaseOrder(): array
    {
        return $this->select('client_delivery_notes.*, client_purchase_orders.po_no AS client_po_no, companies.name AS company_name')
            ->join('client_purchase_orders', 'client_purchase_orders.id = client_delivery_notes.client_purchase_order_id')
            ->join('companies', 'companies.id = client_delivery_notes.company_id', 'left')
            ->orderBy('client_delivery_notes.created_at', 'DESC')->findAll();
    }

    public function detail(int $id): ?array
    {
        return $this->select('client_delivery_notes.*, client_purchase_orders.po_no AS client_po_no, client_purchase_orders.po_date, companies.name AS company_name, companies.address AS company_address, companies.phone AS company_phone')
            ->join('client_purchase_orders', 'client_purchase_orders.id = client_delivery_notes.client_purchase_order_id')
            ->join('companies', 'companies.id = client_delivery_notes.company_id', 'left')->find($id);
    }

    public function listPage(string $search, int $start, int $length, string $orderColumn = 'created_at', string $direction = 'DESC'): array
    {
        $allowed = ['delivery_no' => 'client_delivery_notes.delivery_no', 'client_po_no' => 'client_purchase_orders.po_no', 'company' => 'companies.name', 'delivery_date' => 'client_delivery_notes.delivery_date', 'recipient' => 'client_delivery_notes.recipient_name', 'status' => 'client_delivery_notes.status', 'created_at' => 'client_delivery_notes.created_at'];
        $builder = $this->select('client_delivery_notes.*, client_purchase_orders.po_no AS client_po_no, companies.name AS company_name')
            ->join('client_purchase_orders', 'client_purchase_orders.id = client_delivery_notes.client_purchase_order_id')
            ->join('companies', 'companies.id = client_delivery_notes.company_id', 'left');
        if ($search !== '') $builder->groupStart()->like('client_delivery_notes.delivery_no', $search)->orLike('client_purchase_orders.po_no', $search)->orLike('companies.name', $search)->orLike('client_delivery_notes.recipient_name', $search)->groupEnd();
        return $builder->orderBy($allowed[$orderColumn] ?? $allowed['created_at'], $direction === 'ASC' ? 'ASC' : 'DESC')->findAll(max(1, min($length, 100)), max(0, $start));
    }

    public function countFiltered(string $search): int
    {
        $builder = $this->join('client_purchase_orders', 'client_purchase_orders.id = client_delivery_notes.client_purchase_order_id')->join('companies', 'companies.id = client_delivery_notes.company_id', 'left');
        if ($search !== '') $builder->groupStart()->like('client_delivery_notes.delivery_no', $search)->orLike('client_purchase_orders.po_no', $search)->orLike('companies.name', $search)->orLike('client_delivery_notes.recipient_name', $search)->groupEnd();
        return $builder->countAllResults();
    }
}
