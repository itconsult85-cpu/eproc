<?php
require __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
function esc($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function public_id($id) { return (string) $id; }
function site_url($value='') { return '/' . ltrim($value, '/'); }
function base_url($value='') { return '/' . ltrim($value, '/'); }
$settings = ['company_name'=>'Contoh Perusahaan','office_1'=>'Jl. Contoh No. 1','office_2'=>'Jakarta','phone'=>'021-123456','email'=>'info@example.test'];
$quotation = ['quotation_no'=>'Q-TEST-001','issue_date'=>'2026-10-07','customer_name'=>'Customer Test','company_name'=>'Customer Test'];
$items=[];
for ($i=1; $i<=3; $i++) {
  $items[]=['item'=>['product_name'=>'Produk '.$i,'description'=>'Deskripsi produk '.$i], 'product'=>['name'=>'Produk '.$i,'brand'=>'Brand','sku'=>'SKU-'.$i,'description'=>'Deskripsi produk '.$i,'technical_specs'=>json_encode([['label'=>'Spesifikasi','value'=>str_repeat('Nilai spesifikasi panjang. ',12)]]),'applications'=>'Aplikasi produk','standards'=>'Standar produk','datasheet'=>'Catatan produk'], 'imageData'=>null];
}
$logoData=null;
$options = new Options(); $options->set('isRemoteEnabled', false);
$dompdf = new Dompdf($options);
ob_start(); include __DIR__ . '/../../app/Views/quotations/catalog.php'; $html=ob_get_clean();
$dompdf->loadHtml($html); $dompdf->setPaper('A4','portrait'); $dompdf->render();
file_put_contents(__DIR__ . '/quotation-catalog-test.pdf', $dompdf->output());
$pageCount=$dompdf->getCanvas()->get_page_count();
if ($pageCount < 3) { throw new RuntimeException('Expected at least 3 pages, got '.$pageCount); }
echo "quotation pages={$pageCount}\n";

