<?php

namespace App\Models;


class CompanyModel extends BaseModel
{
    protected $table = 'companies';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'name',
        'quotation_prefix',
        'quotation_code',
        'quotation_sequence',
        'address',
        'phone',
        'email',
        'pic_name',
        'pic_phone',
        'notes'
    ];
}
