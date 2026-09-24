<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateFinanceProcurementModules extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'quotation_id' => ['type' => 'INT', 'unsigned' => true],
            'invoice_no' => ['type' => 'VARCHAR', 'constraint' => 80],
            'invoice_date' => ['type' => 'DATE'],
            'due_date' => ['type' => 'DATE', 'null' => true],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'payment_status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'unpaid'],
            'payment_date' => ['type' => 'DATE', 'null' => true],
            'payment_method' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'payment_reference' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true],
            'proof_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true); $this->forge->addKey('quotation_id'); $this->forge->addUniqueKey('invoice_no');
        $this->forge->addForeignKey('quotation_id', 'quotations', 'id', 'CASCADE', 'CASCADE'); $this->forge->createTable('proforma_invoices', true);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'name' => ['type' => 'VARCHAR', 'constraint' => 180],
            'code' => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true], 'address' => ['type' => 'TEXT', 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true], 'email' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true],
            'pic_name' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true], 'payment_terms' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true], 'is_active' => ['type' => 'TINYINT', 'default' => 1], 'created_at' => ['type' => 'DATETIME', 'null' => true], 'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]); $this->forge->addKey('id', true); $this->forge->addKey('name'); $this->forge->createTable('vendors', true);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'vendor_id' => ['type' => 'INT', 'unsigned' => true],
            'po_no' => ['type' => 'VARCHAR', 'constraint' => 80], 'po_date' => ['type' => 'DATE'], 'expected_date' => ['type' => 'DATE', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'draft'], 'subtotal' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'tax_percent' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0], 'tax_amount' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'grand_total' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0], 'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true], 'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]); $this->forge->addKey('id', true); $this->forge->addUniqueKey('po_no'); $this->forge->addKey('vendor_id'); $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'RESTRICT', 'CASCADE'); $this->forge->createTable('purchase_orders', true);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'purchase_order_id' => ['type' => 'INT', 'unsigned' => true], 'product_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'product_name' => ['type' => 'VARCHAR', 'constraint' => 180], 'description' => ['type' => 'TEXT', 'null' => true], 'quantity' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 1], 'unit' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pcs'], 'unit_price' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0], 'line_total' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
        ]); $this->forge->addKey('id', true); $this->forge->addKey('purchase_order_id'); $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'CASCADE', 'CASCADE'); $this->forge->createTable('purchase_order_items', true);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'vendor_id' => ['type' => 'INT', 'unsigned' => true], 'purchase_order_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true], 'bill_no' => ['type' => 'VARCHAR', 'constraint' => 80], 'bill_date' => ['type' => 'DATE'], 'due_date' => ['type' => 'DATE', 'null' => true], 'amount' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0], 'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'unpaid'], 'paid_date' => ['type' => 'DATE', 'null' => true], 'payment_reference' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true], 'proof_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true], 'notes' => ['type' => 'TEXT', 'null' => true], 'created_at' => ['type' => 'DATETIME', 'null' => true], 'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]); $this->forge->addKey('id', true); $this->forge->addUniqueKey('bill_no'); $this->forge->addKey('vendor_id'); $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'RESTRICT', 'CASCADE'); $this->forge->createTable('vendor_bills', true);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true], 'type' => ['type' => 'VARCHAR', 'constraint' => 40], 'title' => ['type' => 'VARCHAR', 'constraint' => 180], 'message' => ['type' => 'TEXT'], 'url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true], 'due_at' => ['type' => 'DATETIME', 'null' => true], 'is_read' => ['type' => 'TINYINT', 'default' => 0], 'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]); $this->forge->addKey('id', true); $this->forge->addKey(['user_id', 'is_read']); $this->forge->createTable('notifications', true);

        $permissions = [
            ['proforma.view','Lihat proforma invoice','Keuangan'],['proforma.edit','Kelola pembayaran proforma','Keuangan'],['proforma.export','Unduh proforma invoice','Keuangan'],
            ['vendors.view','Lihat vendor','Pengadaan'],['vendors.create','Tambah vendor','Pengadaan'],['vendors.edit','Edit vendor','Pengadaan'],['vendors.delete','Hapus vendor','Pengadaan'],
            ['purchase_orders.view','Lihat purchase order','Pengadaan'],['purchase_orders.create','Tambah purchase order','Pengadaan'],['purchase_orders.edit','Edit purchase order','Pengadaan'],['purchase_orders.delete','Hapus purchase order','Pengadaan'],
            ['vendor_bills.view','Lihat tagihan vendor','Keuangan'],['vendor_bills.create','Tambah tagihan vendor','Keuangan'],['vendor_bills.edit','Edit tagihan vendor','Keuangan'],['vendor_bills.delete','Hapus tagihan vendor','Keuangan'],['notifications.view','Lihat notifikasi','Sistem'],
        ];
        foreach ($permissions as $p) $this->db->table('permissions')->ignore(true)->insert(['permission_key'=>$p[0],'label'=>$p[1],'group_name'=>$p[2],'created_at'=>date('Y-m-d H:i:s')]);
    }
    public function down() { foreach (['notifications','vendor_bills','purchase_order_items','purchase_orders','vendors','proforma_invoices'] as $table) $this->forge->dropTable($table, true); }
}
