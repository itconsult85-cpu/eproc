<?php
namespace App\Models;
class VendorModel extends BaseModel
{
    protected $table='vendors'; protected $primaryKey='id'; protected $returnType='array'; protected $useTimestamps=true;
    protected $allowedFields=['name','code','address','phone','email','pic_name','payment_terms','bank_name','bank_account_name','bank_account_number','bank_branch','tax_id','notes','is_active','created_at','updated_at'];
}
