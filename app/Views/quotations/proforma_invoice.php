<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
@page { margin: 28px 35px; } body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color:#222; }
h1 { font-size: 22px; margin:0 0 4px; } h2 { font-size: 15px; margin:0; color:#555; }
table { width:100%; border-collapse:collapse; } th,td { padding:7px; border:1px solid #bbb; } th { background:#eef3f8; text-align:left; }
.no-border td { border:0; padding:2px 0; } .right { text-align:right; } .meta { margin:18px 0; }
.footer { margin-top:35px; text-align:right; } .small { color:#666; font-size:9px; }
</style>
</head>
<body>
<table class="no-border"><tr><td style="width:65%"><h1><?= esc($settings['company_name'] ?? 'Perusahaan') ?></h1><div><?= esc($settings['office_1'] ?? '') ?><br><?= esc($settings['office_2'] ?? '') ?><br><?= esc($settings['phone'] ?? '') ?> <?= esc($settings['email'] ?? '') ?></div></td><td class="right"><h2>PROFORMA INVOICE</h2><strong><?= esc($quotation['quotation_no']) ?></strong><br><?= esc($quotation['issue_date'] ?? date('Y-m-d')) ?></td></tr></table>
<div class="meta"><strong>Ditujukan kepada:</strong><br><?= esc($quotation['customer_name'] ?: $quotation['company_name']) ?><br><?= nl2br(esc($quotation['customer_address'] ?? $quotation['company_address'] ?? '')) ?><br><?= esc($quotation['customer_phone'] ?? $quotation['company_phone'] ?? '') ?></div>
<table><thead><tr><th>No.</th><th>Produk</th><th>Qty</th><th>Harga Satuan</th><th>Diskon</th><th class="right">Jumlah</th></tr></thead><tbody>
<?php foreach ($quotation['items'] as $i => $item): ?><tr><td><?= $i + 1 ?></td><td><?= esc($item['product_name']) ?><br><span class="small"><?= esc($item['description'] ?? '') ?></span></td><td><?= esc($item['quantity']) ?> <?= esc($item['unit']) ?></td><td>Rp <?= number_format((float)$item['unit_price'], 0, ',', '.') ?></td><td><?= number_format((float)$item['discount_percent'], 2, ',', '.') ?>%</td><td class="right">Rp <?= number_format((float)$item['line_total'], 0, ',', '.') ?></td></tr><?php endforeach; ?>
<tr><td colspan="5" class="right"><strong>Subtotal</strong></td><td class="right">Rp <?= number_format((float)$quotation['subtotal'], 0, ',', '.') ?></td></tr><tr><td colspan="5" class="right">PPN (<?= esc($quotation['tax_percent']) ?>%)</td><td class="right">Rp <?= number_format((float)$quotation['tax_amount'], 0, ',', '.') ?></td></tr><tr><td colspan="5" class="right"><strong>Grand Total</strong></td><td class="right"><strong>Rp <?= number_format((float)$quotation['grand_total'], 0, ',', '.') ?></strong></td></tr></tbody></table>
<p><strong>Syarat pembayaran:</strong> <?= esc($quotation['payment_terms'] ?? '-') ?><br><strong>Pengiriman:</strong> <?= esc($quotation['delivery_terms'] ?? '-') ?></p>
<?php if (!empty($settings['bank_name']) || !empty($settings['bank_account_number'])): ?><p style="border:1px solid #bbb;padding:8px;"><strong>Informasi Pembayaran</strong><br>Bank: <?= esc($settings['bank_name'] ?? '-') ?><br>Nama rekening: <?= esc($settings['bank_account_name'] ?? '-') ?><br>No. rekening: <?= esc($settings['bank_account_number'] ?? '-') ?><?= !empty($settings['bank_branch']) ? '<br>Cabang: '.esc($settings['bank_branch']) : '' ?></p><?php endif; ?>
<div class="footer">Hormat kami,<br><br><br><strong><?= esc($settings['signer_name'] ?? '') ?></strong><br><?= esc($settings['signer_phone'] ?? '') ?></div>
</body></html>
