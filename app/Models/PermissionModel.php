<?php
namespace App\Models;
use CodeIgniter\Model;
class PermissionModel extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['permission_key', 'label', 'group_name', 'created_at'];
}
