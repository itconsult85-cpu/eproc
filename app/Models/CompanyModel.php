<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
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
