<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanyToProducts extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('company_id', 'products')) {
            $this->forge->addColumn('products', [
                'company_id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'id',
                ],
            ]);
            $this->db->query('ALTER TABLE products ADD INDEX idx_products_company_id (company_id)');
            $this->db->query('ALTER TABLE products ADD CONSTRAINT fk_products_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL ON UPDATE CASCADE');
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('company_id', 'products')) {
            $this->db->query('ALTER TABLE products DROP FOREIGN KEY fk_products_company');
            $this->db->query('ALTER TABLE products DROP INDEX idx_products_company_id');
            $this->forge->dropColumn('products', 'company_id');
        }
    }
}
