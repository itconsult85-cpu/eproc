<?php

namespace App\Models;

class ProductModel extends BaseModel
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'company_id',
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

    /** Membatasi produk pada perusahaan user atau produk legacy bersama. */
    public function applyCompanyScope(?array $companyIds = null): self
    {
        if ($companyIds === null) {
            return $this;
        }

        $this->groupStart()->where('products.company_id IS NULL', null, false);
        if ($companyIds) {
            $ids = implode(',', array_map('intval', $companyIds));
            $this->orWhere("EXISTS (SELECT 1 FROM product_company_access pca WHERE pca.product_id = products.id AND pca.company_id IN ({$ids}))", null, false);
        }
        $this->groupEnd();

        return $this;
    }

    public function companyIds(int $productId): array
    {
        $rows = $this->db->table('product_company_access')
            ->select('company_id')->where('product_id', $productId)->get()->getResultArray();
        $ids = array_map(static fn (array $row): int => (int) $row['company_id'], $rows);

        $legacy = $this->select('company_id')->find($productId);
        if ($legacy && ! empty($legacy['company_id'])) $ids[] = (int) $legacy['company_id'];

        return array_values(array_unique($ids));
    }

    public function replaceCompanyAccess(int $productId, array $companyIds): void
    {
        $table = $this->db->table('product_company_access');
        $table->where('product_id', $productId)->delete();
        $rows = [];
        foreach (array_unique(array_map('intval', $companyIds)) as $companyId) {
            if ($companyId > 0) $rows[] = ['product_id' => $productId, 'company_id' => $companyId, 'created_at' => date('Y-m-d H:i:s')];
        }
        if ($rows) $table->insertBatch($rows);
    }
}
