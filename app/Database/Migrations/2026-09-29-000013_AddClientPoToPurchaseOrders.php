<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClientPoToPurchaseOrders extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('client_purchase_order_id', 'purchase_orders')) {
            $this->forge->addColumn('purchase_orders', ['client_purchase_order_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'vendor_id']]);
        }
        $database = $this->db->getDatabase();
        $index = $this->db->query("SELECT COUNT(*) AS total FROM information_schema.statistics WHERE table_schema = ? AND table_name = 'purchase_orders' AND index_name = 'idx_purchase_orders_client_po'", [$database])->getRowArray();
        if ((int) ($index['total'] ?? 0) === 0) $this->db->query('ALTER TABLE purchase_orders ADD INDEX idx_purchase_orders_client_po (client_purchase_order_id)');
        $constraint = $this->db->query("SELECT COUNT(*) AS total FROM information_schema.referential_constraints WHERE constraint_schema = ? AND table_name = 'purchase_orders' AND constraint_name = 'fk_purchase_orders_client_po'", [$database])->getRowArray();
        if ((int) ($constraint['total'] ?? 0) === 0) $this->db->query('ALTER TABLE purchase_orders ADD CONSTRAINT fk_purchase_orders_client_po FOREIGN KEY (client_purchase_order_id) REFERENCES client_purchase_orders(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE purchase_orders DROP FOREIGN KEY fk_purchase_orders_client_po');
        $this->db->query('ALTER TABLE purchase_orders DROP INDEX idx_purchase_orders_client_po');
        if ($this->db->fieldExists('client_purchase_order_id', 'purchase_orders')) $this->forge->dropColumn('purchase_orders', 'client_purchase_order_id');
    }
}
