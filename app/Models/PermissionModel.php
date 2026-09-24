<?php

namespace App\Models;


class PermissionModel extends BaseModel
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'permission_key',
        'label',
        'group_name',
        'created_at'
    ];
}