<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddFinalQuotationSnapshot extends Migration
{
 public function up(){if(! $this->db->fieldExists('final_snapshot_json','quotations'))$this->forge->addColumn('quotations',['final_snapshot_json'=>['type'=>'LONGTEXT','null'=>true,'after'=>'master_snapshot_json']]);}
 public function down(){$this->forge->dropColumn('quotations','final_snapshot_json');}
}
