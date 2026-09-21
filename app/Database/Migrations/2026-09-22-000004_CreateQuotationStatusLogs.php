<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuotationStatusLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'quotation_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'from_status' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'to_status' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'changed_by' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'default' => 'system',
            ],
            'reason' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('quotation_id');
        $this->forge->addForeignKey('quotation_id', 'quotations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quotation_status_logs');
    }

    public function down()
    {
        $this->forge->dropTable('quotation_status_logs', true);
    }
}
