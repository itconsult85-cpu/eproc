<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBastDocuments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'bast_no' => ['type' => 'VARCHAR', 'constraint' => 100],
            'source_type' => ['type' => 'VARCHAR', 'constraint' => 30],
            'source_id' => ['type' => 'INT', 'unsigned' => true],
            'source_no' => ['type' => 'VARCHAR', 'constraint' => 100],
            'source_title' => ['type' => 'VARCHAR', 'constraint' => 220, 'null' => true],
            'source_date' => ['type' => 'DATE', 'null' => true],
            'company_id' => ['type' => 'INT', 'unsigned' => true],
            'handover_date' => ['type' => 'DATE'],
            'location' => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true],
            'recipient_name' => ['type' => 'VARCHAR', 'constraint' => 160],
            'recipient_position' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'handed_over_by' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true],
            'handed_over_position' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'completed'],
            'items_json' => ['type' => 'LONGTEXT'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_by' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('source_id');
        $this->forge->addKey('source_type');
        $this->forge->addKey('company_id');
        $this->forge->addKey('handover_date');
        $this->forge->addUniqueKey('bast_no');
        $this->forge->addForeignKey('company_id', 'companies', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('bast_documents', true);

        $permissions = [
            ['bast.view', 'Lihat berita acara serah terima', 'Pengadaan'],
            ['bast.create', 'Buat berita acara serah terima', 'Pengadaan'],
            ['bast.edit', 'Edit berita acara serah terima', 'Pengadaan'],
            ['bast.delete', 'Hapus berita acara serah terima', 'Pengadaan'],
            ['bast.export', 'Unduh PDF berita acara serah terima', 'Pengadaan'],
        ];
        foreach ($permissions as $permission) {
            if (! $this->db->table('permissions')->where('permission_key', $permission[0])->countAllResults()) {
                $this->db->table('permissions')->insert(['permission_key' => $permission[0], 'label' => $permission[1], 'group_name' => $permission[2], 'created_at' => date('Y-m-d H:i:s')]);
            }
        }
    }

    public function down()
    {
        $this->forge->dropTable('bast_documents', true);
        $this->db->table('permissions')->whereIn('permission_key', ['bast.view', 'bast.create', 'bast.edit', 'bast.delete', 'bast.export'])->delete();
    }
}
