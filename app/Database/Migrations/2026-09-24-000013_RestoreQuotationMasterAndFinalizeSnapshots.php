<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class RestoreQuotationMasterAndFinalizeSnapshots extends Migration
{
 public function up(){
  $rows=$this->db->table('quotations')->where('status','approved')->where('final_snapshot_json IS NULL',null,false)->get()->getResultArray();
  foreach($rows as $quotation){$items=$this->db->table('quotation_items')->where('quotation_id',$quotation['id'])->get()->getResultArray();$final=['payment_terms'=>$quotation['payment_terms'],'delivery_terms'=>$quotation['delivery_terms'],'notes'=>$quotation['notes'],'tax_percent'=>$quotation['tax_percent'],'subtotal'=>$quotation['subtotal'],'tax_amount'=>$quotation['tax_amount'],'grand_total'=>$quotation['grand_total'],'items'=>$items];$this->db->table('quotations')->where('id',$quotation['id'])->update(['final_snapshot_json'=>json_encode($final,JSON_UNESCAPED_UNICODE)]);}
  $master=['payment_terms'=>'Transfer 45 day after invoice','delivery_terms'=>'10 days','notes'=>'','tax_percent'=>11,'subtotal'=>41130000,'tax_amount'=>4524300,'grand_total'=>45654300,'items'=>[['quotation_id'=>1,'product_id'=>2,'product_name'=>'Safety Boot Cougar 1911 Toe Cap','description'=>'BOOTS,SAFETY:HD:43;HIGH NITRILE','quantity'=>60,'unit'=>'PAA','unit_price'=>317000,'discount_percent'=>0,'line_total'=>19020000],['quotation_id'=>1,'product_id'=>1,'product_name'=>'Safety Boot Cheetah 7288C','description'=>'BOOTS,SAFETY;STEEL TOE CAP SHORT SIZE 38','quantity'=>30,'unit'=>'PC','unit_price'=>737000,'discount_percent'=>0,'line_total'=>22110000]]];$this->db->table('quotations')->where('id',1)->where('quotation_no','CCIP00103/TRE-ICA/IX/2026')->update(['master_snapshot_json'=>json_encode($master,JSON_UNESCAPED_UNICODE)]);
 }
 public function down(){}
}
