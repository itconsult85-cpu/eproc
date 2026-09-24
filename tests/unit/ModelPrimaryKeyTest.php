<?php

use App\Models\AuthAuditLogModel;
use App\Models\CompanyModel;
use App\Models\PermissionModel;
use App\Models\ProductModel;
use App\Models\QuotationItemModel;
use App\Models\QuotationModel;
use App\Models\QuotationNegotiationModel;
use App\Models\QuotationSettingModel;
use App\Models\QuotationStatusLogModel;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ModelPrimaryKeyTest extends CIUnitTestCase
{
    public function testCrudModelsExposePrimaryKeyForPublicIdResolver(): void
    {
        $models = [
            new AuthAuditLogModel(),
            new CompanyModel(),
            new PermissionModel(),
            new ProductModel(),
            new QuotationItemModel(),
            new QuotationModel(),
            new QuotationNegotiationModel(),
            new QuotationSettingModel(),
            new QuotationStatusLogModel(),
            new UserModel(),
        ];

        foreach ($models as $model) {
            $this->assertSame('id', $model->getPrimaryKey(), $model::class);
        }
    }
}
