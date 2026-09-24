<?php
namespace App\Models;
class ProformaInvoiceModel extends BaseModel
{
    protected $table='proforma_invoices'; protected $primaryKey='id'; protected $returnType='array'; protected $useTimestamps=true;
    protected $allowedFields=['quotation_id','invoice_no','invoice_date','due_date','amount','payment_status','payment_date','payment_method','payment_reference','proof_path','notes','created_at','updated_at'];
    public function withQuotation(): array { return $this->select('proforma_invoices.*, quotations.quotation_no, quotations.title, companies.name AS company_name')->join('quotations','quotations.id=proforma_invoices.quotation_id')->join('companies','companies.id=quotations.company_id')->orderBy('proforma_invoices.created_at','DESC')->findAll(); }
}
