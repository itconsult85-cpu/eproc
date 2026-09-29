<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientPurchaseOrders extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'quotation_id' => ['type' => 'INT', 'unsigned' => true],
            'company_id' => ['type' => 'INT', 'unsigned' => true],
            'po_no' => ['type' => 'VARCHAR', 'constraint' => 100],
            'po_date' => ['type' => 'DATE'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'received'],
            'subtotal' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'tax_percent' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'tax_amount' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'grand_total' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true); $this->forge->addUniqueKey('po_no'); $this->forge->addKey('quotation_id'); $this->forge->addKey('company_id');
        $this->forge->addForeignKey('quotation_id', 'quotations', 'id', 'RESTRICT', 'CASCADE'); $this->forge->addForeignKey('company_id', 'companies', 'id', 'RESTRICT', 'CASCADE'); $this->forge->createTable('client_purchase_orders', true);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'client_purchase_order_id' => ['type' => 'INT', 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'product_name' => ['type' => 'VARCHAR', 'constraint' => 180],
            'description' => ['type' => 'TEXT', 'null' => true],
            'quantity' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 1],
            'unit' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pcs'],
            'unit_price' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'discount_percent' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'line_total' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
        ]);
        $this->forge->addKey('id', true); $this->forge->addKey('client_purchase_order_id'); $this->forge->addForeignKey('client_purchase_order_id', 'client_purchase_orders', 'id', 'CASCADE', 'CASCADE'); $this->forge->createTable('client_purchase_order_items', true);

        foreach ([['client_po.view', 'Lihat PO IN klien'], ['client_po.create', 'Buat PO IN klien'], ['client_po.edit', 'Edit PO IN klien'], ['client_po.delete', 'Hapus PO IN klien']] as [$key, $label]) {
            if (! $this->db->table('permissions')->where('permission_key', $key)->countAllResults()) $this->db->table('permissions')->insert(['permission_key' => $key, 'label' => $label, 'group_name' => 'Penjualan']);
        }
    }

    public function down()
    {
        $this->forge->dropTable('client_purchase_order_items', true); $this->forge->dropTable('client_purchase_orders', true);
        $this->db->table('permissions')->whereIn('permission_key', ['client_po.view', 'client_po.create', 'client_po.edit', 'client_po.delete'])->delete();
    }
}
