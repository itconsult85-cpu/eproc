<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddMasterQuotationSnapshot extends Migration
{
 public function up(){
  $this->forge->addColumn('quotations',['master_snapshot_json'=>['type'=>'LONGTEXT','null'=>true,'after'=>'bank_account_id']]);
  $this->db->query("UPDATE quotations q SET q.master_snapshot_json = JSON_OBJECT('payment_terms', q.payment_terms, 'delivery_terms', q.delivery_terms, 'notes', q.notes, 'tax_percent', q.tax_percent, 'subtotal', q.subtotal, 'tax_amount', q.tax_amount, 'grand_total', q.grand_total, 'items', COALESCE((SELECT JSON_ARRAYAGG(JSON_OBJECT('product_id', qi.product_id, 'product_name', qi.product_name, 'description', qi.description, 'quantity', qi.quantity, 'unit', qi.unit, 'unit_price', qi.unit_price, 'discount_percent', qi.discount_percent, 'line_total', qi.line_total)) FROM quotation_items qi WHERE qi.quotation_id = q.id), JSON_ARRAY())) WHERE q.master_snapshot_json IS NULL");
 }
 public function down(){$this->forge->dropColumn('quotations','master_snapshot_json');}
}
