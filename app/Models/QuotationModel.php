<?php

namespace App\Models;

use CodeIgniter\Model;

class QuotationModel extends Model
{
    protected $table = 'quotations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['company_id', 'quotation_no', 'customer_name', 'customer_address', 'customer_phone', 'attention', 'title', 'issue_date', 'valid_until', 'validity_days', 'payment_terms', 'delivery_terms', 'notes', 'status', 'subtotal', 'tax_percent', 'tax_amount', 'grand_total'];

    public function withCompany(): array
    {
        return $this->select('quotations.*, companies.name AS company_name')
            ->join('companies', 'companies.id = quotations.company_id', 'left')
            ->orderBy('quotations.created_at', 'DESC')->findAll();
    }

    public function detail(int $id): ?array
    {
        $quotation = $this->select('quotations.*, companies.name AS company_name, companies.address AS company_address, companies.phone AS company_phone, companies.email AS company_email, companies.pic_name')
            ->join('companies', 'companies.id = quotations.company_id', 'left')->find($id);
        if ($quotation === null) return null;
        $quotation['items'] = (new QuotationItemModel())->where('quotation_id', $id)->findAll();
        return $quotation;
    }
}
