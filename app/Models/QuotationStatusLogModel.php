<?php

namespace App\Models;

use CodeIgniter\Model;

class QuotationStatusLogModel extends Model
{
    protected $table = 'quotation_status_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'quotation_id',
        'from_status',
        'to_status',
        'changed_by',
        'reason',
        'created_at',
    ];
}
