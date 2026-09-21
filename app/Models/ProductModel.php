<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['sku', 'name', 'brand', 'description', 'datasheet', 'image_url', 'cost_price', 'selling_price', 'store_name', 'store_url', 'store_phone', 'store_pic', 'is_active'];
}

