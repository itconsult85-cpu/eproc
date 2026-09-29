<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Removes the obsolete owner-company relation from existing installations.
 *
 * The create migration no longer creates this column. This migration handles
 * databases that were initialized before the tender archive became internal.
 */
class RemoveTenderDocumentCompanyId extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('tender_documents')
            || ! $this->db->fieldExists('company_id', 'tender_documents')) {
            return;
        }

        $constraints = $this->db->query(
            'SELECT CONSTRAINT_NAME
             FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME IS NOT NULL',
            ['tender_documents', 'company_id']
        )->getResultArray();

        foreach ($constraints as $constraint) {
            $name = preg_replace('/[^A-Za-z0-9_]/', '', (string) ($constraint['CONSTRAINT_NAME'] ?? ''));
            if ($name !== '') {
                $this->db->query('ALTER TABLE `tender_documents` DROP FOREIGN KEY `' . $name . '`');
            }
        }

        $indexes = $this->db->query(
            'SELECT DISTINCT INDEX_NAME
             FROM INFORMATION_SCHEMA.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?',
            ['tender_documents', 'company_id']
        )->getResultArray();

        foreach ($indexes as $index) {
            $name = preg_replace('/[^A-Za-z0-9_]/', '', (string) ($index['INDEX_NAME'] ?? ''));
            if ($name !== '' && $name !== 'PRIMARY') {
                $this->db->query('ALTER TABLE `tender_documents` DROP INDEX `' . $name . '`');
            }
        }

        $this->forge->dropColumn('tender_documents', 'company_id');
    }

    public function down()
    {
        if (! $this->db->tableExists('tender_documents')
            || $this->db->fieldExists('company_id', 'tender_documents')) {
            return;
        }

        $this->forge->addColumn('tender_documents', [
            'company_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'id'],
        ]);
        $this->forge->addKey('company_id');
    }
}
