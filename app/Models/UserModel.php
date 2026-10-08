<?php

namespace App\Models;


class UserModel extends BaseModel
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

    public function companyIds(int $userId): array
    {
        return array_map(
            static fn(array $row): int => (int) $row['company_id'],
            $this->db->table('user_company_access')->select('company_id')->where('user_id', $userId)->get()->getResultArray()
        );
    }

    public function companies(int $userId): array
    {
        return $this->db->table('companies c')
            ->select('c.*')
            ->join('user_company_access uca', 'uca.company_id = c.id')
            ->where('uca.user_id', $userId)
            ->orderBy('c.name', 'ASC')
            ->get()->getResultArray();
    }

    public function replaceCompanyAccess(int $userId, array $companyIds): void
    {
        $db = $this->db;
        $db->table('user_company_access')->where('user_id', $userId)->delete();
        $rows = [];
        $now = date('Y-m-d H:i:s');
        foreach (array_unique(array_map('intval', $companyIds)) as $companyId) {
            if ($companyId > 0) $rows[] = ['user_id' => $userId, 'company_id' => $companyId, 'created_at' => $now];
        }
        if ($rows) $db->table('user_company_access')->insertBatch($rows);
    }
}
