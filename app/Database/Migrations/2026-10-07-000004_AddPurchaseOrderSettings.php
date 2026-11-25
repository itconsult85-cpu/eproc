<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPurchaseOrderSettings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'TINYINT', 'unsigned' => true],
            'company_name' => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true],
            'office_1' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'office_2' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true],
            'tax_id' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'logo_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'signature_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'stamp_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'document_title' => ['type' => 'VARCHAR', 'constraint' => 120, 'default' => 'PURCHASE ORDER'],
            'signer_name' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'signer_phone' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'default_payment_terms' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'default_delivery_terms' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'default_tax_percent' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 11],
            'default_pph_percent' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('purchase_order_settings', true);
        $this->db->table('purchase_order_settings')->insert([
            'id' => 1,
            'document_title' => 'PURCHASE ORDER',
            'default_tax_percent' => 11,
            'default_pph_percent' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $this->db->table('permissions')->ignore(true)->insert([
            'permission_key' => 'settings.purchase_order',
            'label' => 'Kelola setting purchase order',
            'group_name' => 'Pengadaan',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('purchase_order_settings', true);
    }
}
