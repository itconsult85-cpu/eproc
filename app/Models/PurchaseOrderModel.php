<?php
namespace App\Models;
class PurchaseOrderModel extends BaseModel
{
    protected $table='purchase_orders'; protected $primaryKey='id'; protected $returnType='array'; protected $useTimestamps=true;
    protected $allowedFields=['vendor_id','po_no','po_date','expected_date','status','subtotal','tax_percent','use_ppn','ppn_percent','use_pph','pph_percent','tax_amount','pph_amount','grand_total','notes','created_at','updated_at'];
    public function withVendor(): array { return $this->select('purchase_orders.*, vendors.name AS vendor_name')->join('vendors','vendors.id=purchase_orders.vendor_id')->orderBy('purchase_orders.created_at','DESC')->findAll(); }
    public function detail(int $id): ?array { $row=$this->select('purchase_orders.*, vendors.name AS vendor_name')->join('vendors','vendors.id=purchase_orders.vendor_id')->find($id); if(!$row)return null; $row['items']=(new PurchaseOrderItemModel())->where('purchase_order_id',$id)->findAll(); return $row; }
}
