<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEprocurementTables extends Migration
{
    public function up()
    {
        $this->forge->addField(['id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'name' => ['type' => 'VARCHAR', 'constraint' => 160], 'address' => ['type' => 'TEXT', 'null' => true], 'phone' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true], 'email' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true], 'pic_name' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true], 'pic_phone' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true], 'notes' => ['type' => 'TEXT', 'null' => true], 'created_at' => ['type' => 'DATETIME', 'null' => true], 'updated_at' => ['type' => 'DATETIME', 'null' => true]]);
        $this->forge->addKey('id', true); $this->forge->addUniqueKey('name'); $this->forge->createTable('companies');

        $this->forge->addField(['id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'sku' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true], 'name' => ['type' => 'VARCHAR', 'constraint' => 180], 'brand' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true], 'description' => ['type' => 'TEXT', 'null' => true], 'datasheet' => ['type' => 'TEXT', 'null' => true], 'image_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true], 'cost_price' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0], 'selling_price' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0], 'store_name' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true], 'store_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true], 'store_phone' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true], 'store_pic' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true], 'is_active' => ['type' => 'BOOLEAN', 'default' => true], 'created_at' => ['type' => 'DATETIME', 'null' => true], 'updated_at' => ['type' => 'DATETIME', 'null' => true]]);
        $this->forge->addKey('id', true); $this->forge->addKey('sku'); $this->forge->createTable('products');

        $this->forge->addField(['id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'company_id' => ['type' => 'INT', 'unsigned' => true], 'quotation_no' => ['type' => 'VARCHAR', 'constraint' => 60], 'customer_name' => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true], 'customer_address' => ['type' => 'TEXT', 'null' => true], 'customer_phone' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true], 'title' => ['type' => 'VARCHAR', 'constraint' => 220], 'valid_until' => ['type' => 'DATE', 'null' => true], 'notes' => ['type' => 'TEXT', 'null' => true], 'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'draft'], 'subtotal' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0], 'tax_percent' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0], 'tax_amount' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0], 'grand_total' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0], 'created_at' => ['type' => 'DATETIME', 'null' => true], 'updated_at' => ['type' => 'DATETIME', 'null' => true]]);
        $this->forge->addKey('id', true); $this->forge->addKey('company_id'); $this->forge->addForeignKey('company_id', 'companies', 'id', 'CASCADE', 'RESTRICT'); $this->forge->createTable('quotations');

        $this->forge->addField(['id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'quotation_id' => ['type' => 'INT', 'unsigned' => true], 'product_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true], 'product_name' => ['type' => 'VARCHAR', 'constraint' => 180], 'description' => ['type' => 'TEXT', 'null' => true], 'quantity' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 1], 'unit' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pcs'], 'unit_price' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0], 'discount_percent' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0], 'line_total' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0]]);
        $this->forge->addKey('id', true); $this->forge->addKey('quotation_id'); $this->forge->addForeignKey('quotation_id', 'quotations', 'id', 'CASCADE', 'CASCADE'); $this->forge->createTable('quotation_items');
    }

    public function down()
    {
        $this->forge->dropTable('quotation_items', true); $this->forge->dropTable('quotations', true); $this->forge->dropTable('products', true); $this->forge->dropTable('companies', true);
    }
}

