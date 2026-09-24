<?php

namespace App\Models;

use App\Models\QuotationNegotiationModel;

class QuotationModel extends BaseModel
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
        'grand_total',
        'bank_account_id',
        'master_snapshot_json'
    ];

    private const STATUS_TRANSITIONS = [
        'draft' => ['sent', 'approved', 'negotiation', 'expired'],
        'sent' => ['approved', 'rejected', 'negotiation', 'expired'],
        'negotiation' => ['approved', 'rejected', 'sent', 'expired'],
        'approved' => [],
        'rejected' => [],
        'expired' => ['sent'],
    ];

    public function withCompany(): array
    {
        return $this->select('quotations.*, companies.name AS company_name')
            ->join('companies', 'companies.id = quotations.company_id', 'left')
            ->orderBy('quotations.created_at', 'DESC')->findAll();
    }

    public function detail(int $id): ?array
    {
        $quotation = $this->select('quotations.*, companies.name AS company_name, companies.address AS company_address, companies.phone AS company_phone, companies.email AS company_email, companies.pic_name, company_bank_accounts.bank_name, company_bank_accounts.account_name AS bank_account_name, company_bank_accounts.account_number AS bank_account_number, company_bank_accounts.branch AS bank_branch, company_bank_accounts.currency AS bank_currency')
            ->join('companies', 'companies.id = quotations.company_id', 'left')->join('company_bank_accounts', 'company_bank_accounts.id = quotations.bank_account_id', 'left')->find($id);
        if ($quotation === null) return null;
        $quotation['items'] = (new QuotationItemModel())->where('quotation_id', $id)->findAll();
        $quotation['negotiations'] = (new QuotationNegotiationModel())->forQuotation($id);
        return $quotation;
    }

    public function nextQuotationNumber(int $companyId, string $issueDate): string
    {
        $db = $this->db;
        $company = $db->query('SELECT * FROM companies WHERE id = ? FOR UPDATE', [$companyId])->getRowArray();
        if (! $company) {
            throw new \InvalidArgumentException('Perusahaan quotation tidak ditemukan.');
        }

        $prefix = strtoupper(trim((string) ($company['quotation_prefix'] ?? 'CCIP'))) ?: 'CCIP';
        $code = strtoupper(trim((string) ($company['quotation_code'] ?? '')));
        if ($code === '') {
            $words = preg_split('/\s+/', trim((string) preg_replace('/\bPT\.?\s*/i', '', $company['name'] ?? '')));
            $initials = '';
            foreach ((array) $words as $word) {
                if ($word !== '') $initials .= strtoupper($word[0]);
            }
            $code = 'TRE-' . ($initials ?: 'GEN');
        }

        $monthRoman = [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $timestamp = strtotime($issueDate) ?: time();
        $day = date('d', $timestamp);
        $month = $monthRoman[(int) date('n', $timestamp)];
        $year = date('Y', $timestamp);
        $sequence = (int) ($company['quotation_sequence'] ?? 0);

        $rows = $db->table('quotations')->select('quotation_no')->where('company_id', $companyId)->get()->getResultArray();
        $pattern = '/^' . preg_quote($prefix, '/') . '(\d+)\d{2}\/' . preg_quote($code, '/') . '\//';
        foreach ($rows as $row) {
            if (preg_match($pattern, (string) ($row['quotation_no'] ?? ''), $matches)) {
                $sequence = max($sequence, (int) $matches[1]);
            }
        }
        $sequence++;
        $db->table('companies')->where('id', $companyId)->update(['quotation_sequence' => $sequence]);

        return $prefix . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT) . $day . '/' . $code . '/' . $month . '/' . $year;
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
        // Masa berlaku adalah peringatan komersial, bukan pemblokiran otomatis.
        // Perpanjangan harus dilakukan eksplisit oleh user agar ada jejak audit.
        return 0;
    }
}
