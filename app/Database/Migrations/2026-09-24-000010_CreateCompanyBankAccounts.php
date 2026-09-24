<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateCompanyBankAccounts extends Migration
{
 public function up(){
  $this->forge->addField(['id'=>['type'=>'INT','unsigned'=>true,'auto_increment'=>true],'bank_name'=>['type'=>'VARCHAR','constraint'=>120],'account_name'=>['type'=>'VARCHAR','constraint'=>160],'account_number'=>['type'=>'VARCHAR','constraint'=>80],'branch'=>['type'=>'VARCHAR','constraint'=>120,'null'=>true],'currency'=>['type'=>'VARCHAR','constraint'=>10,'default'=>'IDR'],'is_active'=>['type'=>'TINYINT','default'=>1],'is_default'=>['type'=>'TINYINT','default'=>0],'notes'=>['type'=>'VARCHAR','constraint'=>255,'null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addKey('id',true);$this->forge->createTable('company_bank_accounts');
  $this->forge->addColumn('quotations',['bank_account_id'=>['type'=>'INT','unsigned'=>true,'null'=>true,'after'=>'grand_total']]);
  $this->db->query("INSERT INTO company_bank_accounts (bank_name, account_name, account_number, branch, is_active, is_default, created_at, updated_at) SELECT bank_name, bank_account_name, bank_account_number, bank_branch, 1, 1, NOW(), NOW() FROM quotation_settings WHERE id = 1 AND COALESCE(bank_name, '') <> '' AND COALESCE(bank_account_number, '') <> ''");
 }
 public function down(){$this->forge->dropColumn('quotations','bank_account_id');$this->forge->dropTable('company_bank_accounts',true);}
}
