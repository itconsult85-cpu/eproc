<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddMasterQuotationSnapshot extends Migration
{
 public function up(){
  if (! $this->db->fieldExists('master_snapshot_json', 'quotations')) $this->forge->addColumn('quotations',['master_snapshot_json'=>['type'=>'LONGTEXT','null'=>true,'after'=>'bank_account_id']]);
  $quotations = $this->db->table('quotations')->where('master_snapshot_json IS NULL', null, false)->get()->getResultArray();
  foreach ($quotations as $quotation) {
   $items = $this->db->table('quotation_items')->where('quotation_id', $quotation['id'])->get()->getResultArray();
   $snapshot = ['payment_terms'=>$quotation['payment_terms'],'delivery_terms'=>$quotation['delivery_terms'],'notes'=>$quotation['notes'],'tax_percent'=>$quotation['tax_percent'],'subtotal'=>$quotation['subtotal'],'tax_amount'=>$quotation['tax_amount'],'grand_total'=>$quotation['grand_total'],'items'=>$items];
   $this->db->table('quotations')->where('id', $quotation['id'])->update(['master_snapshot_json'=>json_encode($snapshot, JSON_UNESCAPED_UNICODE)]);
  }
 }
 public function down(){$this->forge->dropColumn('quotations','master_snapshot_json');}
}
