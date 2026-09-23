<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthAuditLogModel extends Model
{
    protected $table = 'auth_audit_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'user_id',
        'username',
        'event',
        'ip_address',
        'user_agent',
        'details',
        'created_at'
    ];
}