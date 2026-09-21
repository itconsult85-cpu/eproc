<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGenericDatasheetFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'technical_specs' => ['type' => 'TEXT', 'null' => true, 'after' => 'datasheet'],
            'applications' => ['type' => 'TEXT', 'null' => true, 'after' => 'technical_specs'],
            'standards' => ['type' => 'TEXT', 'null' => true, 'after' => 'applications'],
            'datasheet_file_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true, 'after' => 'standards'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['technical_specs', 'applications', 'standards', 'datasheet_file_path']);
    }
}
