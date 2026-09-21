<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQuotationSettingsAndMedia extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'image_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true, 'after' => 'image_url'],
            'video_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true, 'after' => 'image_path'],
            'video_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true, 'after' => 'video_url'],
        ]);
        $this->forge->addColumn('quotations', [
            'attention' => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true, 'after' => 'customer_phone'],
            'issue_date' => ['type' => 'DATE', 'null' => true, 'after' => 'title'],
            'validity_days' => ['type' => 'INT', 'constraint' => 5, 'null' => true, 'after' => 'valid_until'],
            'payment_terms' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'validity_days'],
            'delivery_terms' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'payment_terms'],
        ]);
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
            'signer_name' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'signer_phone' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'default_payment_terms' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'default_validity_days' => ['type' => 'INT', 'constraint' => 5, 'default' => 10],
            'default_delivery_terms' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'default_tax_percent' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 11],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('quotation_settings');
        $this->db->table('quotation_settings')->insert(['id' => 1, 'default_validity_days' => 10, 'default_tax_percent' => 11, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function down()
    {
        $this->forge->dropTable('quotation_settings', true);
        $this->forge->dropColumn('quotations', ['attention', 'issue_date', 'validity_days', 'payment_terms', 'delivery_terms']);
        $this->forge->dropColumn('products', ['image_path', 'video_url', 'video_path']);
    }
}
