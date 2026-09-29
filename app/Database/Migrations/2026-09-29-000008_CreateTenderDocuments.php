<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTenderDocuments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tender_no' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 220],
            'procurement_method' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'issuer_name' => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'issue_date' => ['type' => 'DATE', 'null' => true],
            'valid_until' => ['type' => 'DATE', 'null' => true],
            'submission_deadline' => ['type' => 'DATETIME', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'draft'],
            'contact_name' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'contact_email' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true],
            'contact_phone' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'file_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'original_file_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_mime' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'file_size' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_by' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('status');
        $this->forge->addKey('valid_until');
        $this->forge->createTable('tender_documents');

        if ($this->db->tableExists('permissions')) {
            $this->db->query("INSERT IGNORE INTO permissions (permission_key, label, group_name, created_at) VALUES
                ('tenders.view', 'Lihat dokumen tender', 'Tender', NOW()),
                ('tenders.create', 'Tambah dokumen tender', 'Tender', NOW()),
                ('tenders.edit', 'Edit dokumen tender', 'Tender', NOW()),
                ('tenders.delete', 'Hapus dokumen tender', 'Tender', NOW())");
        }
    }

    public function down()
    {
        if ($this->db->tableExists('permissions')) {
            $this->db->table('permissions')->whereIn('permission_key', ['tenders.view', 'tenders.create', 'tenders.edit', 'tenders.delete'])->delete();
        }
        $this->forge->dropTable('tender_documents', true);
    }
}
