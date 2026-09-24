<?php
namespace App\Models;

use CodeIgniter\Model;

class QuotationNegotiationModel extends Model
{
    protected $table = 'quotation_negotiations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'quotation_id', 'round_no', 'status', 'proposed_by', 'customer_message',
        'internal_notes', 'snapshot_json', 'subtotal', 'tax_amount', 'grand_total',
        'created_at', 'responded_at', 'responded_by',
    ];

    public function forQuotation(int $quotationId): array
    {
        return $this->where('quotation_id', $quotationId)->orderBy('round_no', 'DESC')->findAll();
    }

    public function latestPending(int $quotationId): ?array
    {
        return $this->where(['quotation_id' => $quotationId, 'status' => 'pending'])
            ->orderBy('round_no', 'DESC')->first();
    }
}

// EOF
