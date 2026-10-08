<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductCompanyAccess extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'product_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'company_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey(['product_id', 'company_id'], true);
        $this->forge->addKey('company_id');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('company_id', 'companies', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_company_access', true);

        // Backfill relasi dari kolom legacy agar produk lama tetap terkait.
        if ($this->db->fieldExists('company_id', 'products')) {
            $this->db->query(
                'INSERT IGNORE INTO product_company_access (product_id, company_id, created_at)
                 SELECT id, company_id, ? FROM products WHERE company_id IS NOT NULL',
                [date('Y-m-d H:i:s')]
            );
        }
    }

    public function down()
    {
        $this->forge->dropTable('product_company_access', true);
    }
}
