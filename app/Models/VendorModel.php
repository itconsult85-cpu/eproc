<?php
namespace App\Models;
class VendorModel extends BaseModel
{
    protected $table='vendors'; protected $primaryKey='id'; protected $returnType='array'; protected $useTimestamps=true;
    protected $allowedFields=['name','code','address','phone','email','pic_name','payment_terms','notes','is_active','created_at','updated_at'];
}
