-- Jalankan sekali pada database lama yang sudah memiliki tender_documents.company_id.
-- Migration CodeIgniter yang setara: 2026-09-29-000009_RemoveTenderDocumentCompanyId.

SET @fk_name := (
    SELECT CONSTRAINT_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'tender_documents'
      AND COLUMN_NAME = 'company_id'
      AND REFERENCED_TABLE_NAME IS NOT NULL
    LIMIT 1
);
SET @sql := IF(
    @fk_name IS NULL,
    'SELECT 1',
    CONCAT('ALTER TABLE `tender_documents` DROP FOREIGN KEY `', @fk_name, '`')
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @index_name := (
    SELECT INDEX_NAME
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'tender_documents'
      AND COLUMN_NAME = 'company_id'
      AND INDEX_NAME <> 'PRIMARY'
    LIMIT 1
);
SET @sql := IF(
    @index_name IS NULL,
    'SELECT 1',
    CONCAT('ALTER TABLE `tender_documents` DROP INDEX `', @index_name, '`')
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @column_exists := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'tender_documents'
      AND COLUMN_NAME = 'company_id'
);
SET @sql := IF(
    @column_exists = 0,
    'SELECT 1',
    'ALTER TABLE `tender_documents` DROP COLUMN `company_id`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
