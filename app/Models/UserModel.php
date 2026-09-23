<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'username',
        'email',
        'full_name',
        'password_hash',
        'role',
        'avatar_path',
        'is_active',
        'failed_login_attempts',
        'locked_until',
        'last_login_at',
        'last_login_ip'
    ];
    protected $beforeInsert = ['normalize'];
    protected $beforeUpdate = ['normalize'];

    protected function normalize(array $data): array
    {
        if (isset($data['data']['username'])) $data['data']['username'] = strtolower(trim($data['data']['username']));
        if (isset($data['data']['email'])) $data['data']['email'] = strtolower(trim($data['data']['email']));
        return $data;
    }

    public function findByLogin(string $login): ?array
    {
        return $this->groupStart()->where('username', strtolower(trim($login)))->orWhere('email', strtolower(trim($login)))->groupEnd()->first();
    }

    public function permissions(int $userId): array
    {
        return $this->db->table('user_permissions up')->select('p.permission_key')->join('permissions p', 'p.id = up.permission_id')->where('up.user_id', $userId)->get()->getResultArray();
    }
}