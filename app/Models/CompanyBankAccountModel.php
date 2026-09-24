<?php
namespace App\Models;
class CompanyBankAccountModel extends BaseModel
{
 protected $table='company_bank_accounts'; protected $primaryKey='id'; protected $returnType='array'; protected $useTimestamps=true;
 protected $allowedFields=['bank_name','account_name','account_number','branch','currency','is_active','is_default','notes','created_at','updated_at'];
 public function active(): array { return $this->where('is_active',1)->orderBy('is_default','DESC')->orderBy('bank_name','ASC')->findAll(); }
}
