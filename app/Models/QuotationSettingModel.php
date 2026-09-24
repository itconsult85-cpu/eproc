<?php

namespace App\Models;

use CodeIgniter\Model;

class QuotationSettingModel extends Model
{
    protected $table = 'quotation_settings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'company_name',
        'office_1',
        'office_2',
        'phone',
        'email',
        'tax_id',
        'logo_path',
        'signature_path',
        'stamp_path',
        'signer_name',
        'signer_phone',
        'default_payment_terms',
        'default_validity_days',
        'default_delivery_terms',
        'default_tax_percent'
    ];

    public function current(): array
    {
        return $this->find(1)
            ?? $this->orderBy($this->primaryKey, 'ASC')->first()
            ?? ['id' => 1, 'default_validity_days' => 10, 'default_tax_percent' => 11];
    }
}
