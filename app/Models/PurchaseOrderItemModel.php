<?php
namespace App\Models;
class PurchaseOrderItemModel extends BaseModel
{
    protected $table='purchase_order_items'; protected $primaryKey='id'; protected $returnType='array'; protected $useTimestamps=false;
    protected $allowedFields=['purchase_order_id','product_id','product_name','description','quantity','unit','unit_price','line_total'];
}
