<?php

namespace App\Models;


class AuthAuditLogModel extends BaseModel
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