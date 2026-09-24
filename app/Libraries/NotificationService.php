<?php
namespace App\Libraries;
use App\Models\NotificationModel;
class NotificationService
{
    public static function sync(): void
    {
        $db=db_connect();$model=new NotificationModel();$now=date('Y-m-d H:i:s');
        $rows=$db->table('quotations')->select('id,quotation_no,valid_until,status')->whereIn('status',['draft','sent','negotiation'])->where('valid_until <',date('Y-m-d'))->get()->getResultArray();
        foreach($rows as $row)self::add($model,'quotation_expired','Quotation kadaluarsa','Quotation '.$row['quotation_no'].' telah melewati masa berlaku.','/quotations/'.public_id((int)$row['id']),$now);
        $rows=$db->table('proforma_invoices')->whereIn('payment_status',['unpaid','partial'])->where('due_date <',date('Y-m-d'))->get()->getResultArray();
        foreach($rows as $row)self::add($model,'proforma_unpaid','Proforma Invoice belum dibayar','Proforma '.$row['invoice_no'].' belum dibayar atau sudah melewati jatuh tempo.','/proforma-invoices',($row['due_date']??$now).' 00:00:00');
        $rows=$db->table('vendor_bills')->whereIn('status',['unpaid','partial'])->where('due_date <',date('Y-m-d'))->get()->getResultArray();
        foreach($rows as $row)self::add($model,'vendor_bill_unpaid','Tagihan vendor belum dibayar','Tagihan vendor '.$row['bill_no'].' belum dibayar atau sudah melewati jatuh tempo.','/vendor-bills',($row['due_date']??$now).' 00:00:00');
    }
    private static function add(NotificationModel $model,string $type,string $title,string $message,string $url,string $due):void
    {if(!$model->where(['type'=>$type,'url'=>$url,'is_read'=>0])->first())$model->insert(['user_id'=>null,'type'=>$type,'title'=>$title,'message'=>$message,'url'=>$url,'due_at'=>$due,'is_read'=>0,'created_at'=>date('Y-m-d H:i:s')]);}
}
