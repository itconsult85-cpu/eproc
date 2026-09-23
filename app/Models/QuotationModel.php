<?php

namespace App\Models;

use CodeIgniter\Model;

class QuotationModel extends Model
{
    protected $table = 'quotations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'company_id',
        'quotation_no',
        'customer_name',
        'customer_address',
        'customer_phone',
        'attention',
        'title',
        'issue_date',
        'valid_until',
        'validity_days',
        'payment_terms',
        'delivery_terms',
        'notes',
        'status',
        'subtotal',
        'tax_percent',
        'tax_amount',
        'grand_total'
    ];

    private const STATUS_TRANSITIONS = [
        'draft' => ['sent', 'approved', 'expired'],
        'sent' => ['approved', 'rejected', 'expired'],
        'approved' => [],
        'rejected' => [],
        'expired' => [],
    ];

    public function withCompany(): array
    {
        return $this->select('quotations.*, companies.name AS company_name')
            ->join('companies', 'companies.id = quotations.company_id', 'left')
            ->orderBy('quotations.created_at', 'DESC')->findAll();
    }

    public function detail(int $id): ?array
    {
        $quotation = $this->select('quotations.*, companies.name AS company_name, companies.address AS company_address, companies.phone AS company_phone, companies.email AS company_email, companies.pic_name')
            ->join('companies', 'companies.id = quotations.company_id', 'left')->find($id);
        if ($quotation === null) return null;
        $quotation['items'] = (new QuotationItemModel())->where('quotation_id', $id)->findAll();
        return $quotation;
    }

    public function changeStatus(int $id, string $toStatus, string $changedBy = 'system', ?string $reason = null): bool
    {
        $quotation = $this->find($id);
        if (! $quotation) {
            throw new \InvalidArgumentException('Quotation tidak ditemukan.');
        }

        $fromStatus = strtolower((string) ($quotation['status'] ?: 'draft'));
        $toStatus = strtolower(trim($toStatus));
        if (! array_key_exists($fromStatus, self::STATUS_TRANSITIONS) || ! in_array($toStatus, self::STATUS_TRANSITIONS[$fromStatus], true)) {
            throw new \InvalidArgumentException("Perubahan status dari {$fromStatus} ke {$toStatus} tidak diizinkan.");
        }

        $db = $this->db;
        $db->transStart();
        $this->update($id, ['status' => $toStatus]);
        (new QuotationStatusLogModel())->insert([
            'quotation_id' => $id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => $changedBy ?: 'system',
            'reason' => $reason,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException('Perubahan status quotation gagal disimpan.');
        }

        return true;
    }

    public function expireOverdue(): int
    {
        $rows = $this->whereIn('status', ['draft', 'sent'])
            ->where('valid_until IS NOT NULL', null, false)
            ->where('valid_until <', date('Y-m-d'))
            ->findAll();
        $expired = 0;
        foreach ($rows as $row) {
            $this->changeStatus((int) $row['id'], 'expired', 'system', 'Otomatis karena melewati tanggal jatuh tempo.');
            $expired++;
        }

        return $expired;
    }
}