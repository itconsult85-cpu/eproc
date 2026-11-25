<?php

namespace App\Models;

class PurchaseOrderSettingModel extends BaseModel
{
    protected $table = 'purchase_order_settings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'company_name', 'office_1', 'office_2', 'phone', 'email', 'tax_id',
        'logo_path', 'signature_path', 'stamp_path', 'document_title',
        'signer_name', 'signer_phone', 'default_payment_terms',
        'default_delivery_terms', 'default_tax_percent', 'default_pph_percent'
    ];

    public function current(): array
    {
        if (! $this->db->tableExists($this->table)) {
            return ['id' => 1, 'document_title' => 'PURCHASE ORDER', 'default_tax_percent' => 11, 'default_pph_percent' => 0];
        }
        return $this->find(1)
            ?? $this->orderBy($this->primaryKey, 'ASC')->first()
            ?? ['id' => 1, 'document_title' => 'PURCHASE ORDER', 'default_tax_percent' => 11, 'default_pph_percent' => 0];
    }
}
