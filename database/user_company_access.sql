-- Jalankan migration CodeIgniter terlebih dahulu jika memungkinkan.
-- Script ini tidak menghapus data lama.

CREATE TABLE IF NOT EXISTS user_company_access (
    user_id INT UNSIGNED NOT NULL,
    company_id INT UNSIGNED NOT NULL,
    created_at DATETIME NULL,
    PRIMARY KEY (user_id, company_id),
    KEY idx_user_company_access_company_id (company_id),
    CONSTRAINT fk_user_company_access_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_user_company_access_company FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT IGNORE INTO user_company_access (user_id, company_id, created_at)
SELECT u.id, c.id, NOW()
FROM users u CROSS JOIN companies c
WHERE u.role IN ('superadmin', 'admin');

SET @has_company_id = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products' AND COLUMN_NAME = 'company_id'
);
SET @sql = IF(
    @has_company_id = 0,
    'ALTER TABLE products ADD COLUMN company_id INT UNSIGNED NULL AFTER id, ADD INDEX idx_products_company_id (company_id), ADD CONSTRAINT fk_products_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

CREATE TABLE IF NOT EXISTS product_company_access (
    product_id INT UNSIGNED NOT NULL,
    company_id INT UNSIGNED NOT NULL,
    created_at DATETIME NULL,
    PRIMARY KEY (product_id, company_id),
    KEY idx_product_company_access_company_id (company_id),
    CONSTRAINT fk_product_company_access_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_product_company_access_company FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Backfill company_id legacy ke tabel pivot.
INSERT IGNORE INTO product_company_access (product_id, company_id, created_at)
SELECT id, company_id, NOW() FROM products WHERE company_id IS NOT NULL;

-- Produk dengan company_id NULL tetap diperlakukan sebagai katalog legacy/shared.
