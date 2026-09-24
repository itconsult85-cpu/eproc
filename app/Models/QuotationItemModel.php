<?php

namespace App\Models;


class QuotationItemModel extends BaseModel
{
    protected $table = 'quotation_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'quotation_id',
        'product_id',
        'product_name',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'discount_percent',
        'line_total'
    ];
}