<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserSignatureAndPurchaseOrderCreator extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('users') && ! $this->db->fieldExists('signature_path', 'users')) {
            $this->forge->addColumn('users', [
                'signature_path' => [
                    'type' => 'VARCHAR',
                    'constraint' => 500,
                    'null' => true,
                    'after' => 'avatar_path',
                ],
            ]);
        }

        if ($this->db->tableExists('purchase_orders') && ! $this->db->fieldExists('created_by', 'purchase_orders')) {
            $this->forge->addColumn('purchase_orders', [
                'created_by' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'vendor_id',
                ],
            ]);
            $this->forge->addKey('created_by');
            $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        }
    }

    public function down()
    {
        if ($this->db->tableExists('purchase_orders') && $this->db->fieldExists('created_by', 'purchase_orders')) {
            $this->forge->dropForeignKey('purchase_orders', 'purchase_orders_created_by_foreign');
            $this->forge->dropColumn('purchase_orders', 'created_by');
        }
        if ($this->db->tableExists('users') && $this->db->fieldExists('signature_path', 'users')) {
            $this->forge->dropColumn('users', 'signature_path');
        }
    }
}
