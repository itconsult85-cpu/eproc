<?php

namespace App\Models;


class ProductModel extends BaseModel
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'sku',
        'name',
        'brand',
        'description',
        'datasheet',
        'technical_specs',
        'applications',
        'standards',
        'datasheet_file_path',
        'image_url',
        'image_path',
        'video_url',
        'video_path',
        'cost_price',
        'selling_price',
        'store_name',
        'store_url',
        'store_phone',
        'store_pic',
        'is_active'
    ];
}