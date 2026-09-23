<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQuotationNumberConfiguration extends Migration
{
    public function up()
    {
        $this->forge->addColumn('companies', [
            'quotation_prefix' => ['type' => 'VARCHAR', 'constraint' => 12, 'null' => true, 'default' => 'CCIP', 'after' => 'name'],
            'quotation_code' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'quotation_prefix'],
            'quotation_sequence' => ['type' => 'INT', 'unsigned' => true, 'null' => false, 'default' => 0, 'after' => 'quotation_code'],
        ]);
        $this->db->query("UPDATE companies SET quotation_prefix = 'CCIP' WHERE quotation_prefix IS NULL OR quotation_prefix = ''");
        $this->db->query("ALTER TABLE quotations ADD UNIQUE KEY uq_quotations_quotation_no (quotation_no)");
    }

    public function down()
    {
        $this->db->query('ALTER TABLE quotations DROP INDEX uq_quotations_quotation_no');
        $this->forge->dropColumn('companies', ['quotation_prefix', 'quotation_code', 'quotation_sequence']);
    }
}
