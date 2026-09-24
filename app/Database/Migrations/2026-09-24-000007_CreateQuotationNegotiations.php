<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuotationNegotiations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'quotation_id' => ['type' => 'INT', 'unsigned' => true],
            'round_no' => ['type' => 'INT', 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'proposed_by' => ['type' => 'VARCHAR', 'constraint' => 120, 'default' => 'system'],
            'customer_message' => ['type' => 'TEXT', 'null' => true],
            'internal_notes' => ['type' => 'TEXT', 'null' => true],
            'snapshot_json' => ['type' => 'LONGTEXT'],
            'subtotal' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'tax_amount' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'grand_total' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'responded_at' => ['type' => 'DATETIME', 'null' => true],
            'responded_by' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['quotation_id', 'round_no']);
        $this->forge->addForeignKey('quotation_id', 'quotations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quotation_negotiations');
    }

    public function down()
    {
        $this->forge->dropTable('quotation_negotiations', true);
    }
}
