<?php

namespace App\Models;


class QuotationStatusLogModel extends BaseModel
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
