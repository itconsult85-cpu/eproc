<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientDeliveryNotes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'client_purchase_order_id' => ['type' => 'INT', 'unsigned' => true],
            'company_id' => ['type' => 'INT', 'unsigned' => true],
            'delivery_no' => ['type' => 'VARCHAR', 'constraint' => 100],
            'delivery_date' => ['type' => 'DATE'],
            'destination' => ['type' => 'VARCHAR', 'constraint' => 220, 'null' => true],
            'recipient_name' => ['type' => 'VARCHAR', 'constraint' => 160],
            'recipient_position' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'delivered_by' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true],
            'delivered_position' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'draft'],
            'items_json' => ['type' => 'LONGTEXT'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_by' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true); $this->forge->addUniqueKey('delivery_no'); $this->forge->addKey('client_purchase_order_id'); $this->forge->addKey('company_id'); $this->forge->addKey('delivery_date');
        $this->forge->addForeignKey('client_purchase_order_id', 'client_purchase_orders', 'id', 'RESTRICT', 'CASCADE'); $this->forge->addForeignKey('company_id', 'companies', 'id', 'RESTRICT', 'CASCADE'); $this->forge->createTable('client_delivery_notes', true);
        foreach ([['client_delivery_note.view', 'Lihat surat jalan client'], ['client_delivery_note.create', 'Buat surat jalan client'], ['client_delivery_note.edit', 'Edit surat jalan client'], ['client_delivery_note.delete', 'Hapus surat jalan client'], ['client_delivery_note.export', 'Unduh PDF surat jalan client']] as [$key, $label]) {
            if (! $this->db->table('permissions')->where('permission_key', $key)->countAllResults()) $this->db->table('permissions')->insert(['permission_key' => $key, 'label' => $label, 'group_name' => 'Penjualan']);
        }
    }

    public function down()
    {
        $this->forge->dropTable('client_delivery_notes', true);
        $this->db->table('permissions')->whereIn('permission_key', ['client_delivery_note.view', 'client_delivery_note.create', 'client_delivery_note.edit', 'client_delivery_note.delete', 'client_delivery_note.export'])->delete();
    }
}
