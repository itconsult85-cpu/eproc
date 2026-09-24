<?php
namespace App\Models;
class VendorBillModel extends BaseModel
{
    protected $table='vendor_bills'; protected $primaryKey='id'; protected $returnType='array'; protected $useTimestamps=true;
    protected $allowedFields=['vendor_id','purchase_order_id','bill_no','bill_date','due_date','amount','status','paid_date','payment_reference','proof_path','notes','created_at','updated_at'];
    public function withVendor(): array { return $this->select('vendor_bills.*, vendors.name AS vendor_name, purchase_orders.po_no')->join('vendors','vendors.id=vendor_bills.vendor_id')->join('purchase_orders','purchase_orders.id=vendor_bills.purchase_order_id','left')->orderBy('vendor_bills.created_at','DESC')->findAll(); }
}
