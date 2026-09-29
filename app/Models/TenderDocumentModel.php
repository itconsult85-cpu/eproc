<?php
namespace App\Models;

use CodeIgniter\Model;

class TenderDocumentModel extends Model
{
    protected $table = 'tender_documents';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'company_id', 'tender_no', 'title', 'procurement_method', 'issuer_name',
        'description', 'issue_date', 'valid_until', 'submission_deadline', 'status',
        'contact_name', 'contact_email', 'contact_phone', 'notes', 'file_path',
        'original_file_name', 'file_mime', 'file_size', 'created_by',
    ];

    public function withCompany(): array
    {
        return $this->select('tender_documents.*, companies.name AS company_name')
            ->join('companies', 'companies.id = tender_documents.company_id', 'left')
            ->orderBy('tender_documents.created_at', 'DESC')->findAll();
    }

    public function detail(int $id): ?array
    {
        return $this->select('tender_documents.*, companies.name AS company_name')
            ->join('companies', 'companies.id = tender_documents.company_id', 'left')->find($id);
    }
}
