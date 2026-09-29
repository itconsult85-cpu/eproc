<?php

namespace App\Models;

class ClientPurchaseOrderItemModel extends BaseModel
{
    protected $table = 'client_purchase_order_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['client_purchase_order_id', 'product_id', 'product_name', 'description', 'quantity', 'unit', 'unit_price', 'discount_percent', 'line_total'];
}
