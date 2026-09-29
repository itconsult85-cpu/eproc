<?php

namespace App\Models;

class BastModel extends BaseModel
{
    protected $table = 'bast_documents';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'bast_no', 'source_type', 'source_id', 'source_no', 'source_title', 'source_date',
        'company_id', 'handover_date', 'location', 'recipient_name', 'recipient_position',
        'handed_over_by', 'handed_over_position', 'status', 'items_json', 'notes', 'created_by',
        'created_at', 'updated_at'
    ];

    public function listPage(string $search, int $start, int $length, string $orderColumn = 'created_at', string $direction = 'DESC'): array
    {
        $allowed = ['bast_no' => 'bast_no', 'source_no' => 'source_no', 'company' => 'companies.name', 'handover_date' => 'handover_date', 'status' => 'status', 'created_at' => 'bast_documents.created_at'];
        $builder = $this->select('bast_documents.*, companies.name AS company_name')
            ->join('companies', 'companies.id = bast_documents.company_id', 'left');
        if ($search !== '') {
            $builder->groupStart()->like('bast_no', $search)->orLike('source_no', $search)->orLike('source_title', $search)->orLike('companies.name', $search)->orLike('recipient_name', $search)->groupEnd();
        }
        return $builder->orderBy($allowed[$orderColumn] ?? $allowed['created_at'], $direction === 'ASC' ? 'ASC' : 'DESC')->findAll(max(1, min($length, 100)), max(0, $start));
    }

    public function countFiltered(string $search): int
    {
        $builder = $this->join('companies', 'companies.id = bast_documents.company_id', 'left');
        if ($search !== '') $builder->groupStart()->like('bast_no', $search)->orLike('source_no', $search)->orLike('source_title', $search)->orLike('companies.name', $search)->orLike('recipient_name', $search)->groupEnd();
        return $builder->countAllResults();
    }

    public function withCompany(int $id): ?array
    {
        return $this->select('bast_documents.*, companies.name AS company_name, companies.address AS company_address, companies.phone AS company_phone, companies.email AS company_email, companies.pic_name')
            ->join('companies', 'companies.id = bast_documents.company_id', 'left')->find($id);
    }
}
