<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>PO <?= esc($po['po_no']) ?></title>
    <style>
        @page { size: A4; margin: 28px 42px 34px; }
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; color: #111; font-size: 10px; line-height: 1.28; }
        table { width: 100%; border-collapse: collapse; }
        .header { padding-bottom: 4px; }
        .logo-cell { width: 25%; vertical-align: middle; }
        .logo { width: 116px; max-height: 70px; object-fit: contain; }
        .company-cell { width: 75%; text-align: center; vertical-align: middle; }
        .company-name { font-family: "Times New Roman", Times, serif; font-size: 19px; font-weight: bold; letter-spacing: .3px; }
        .company-info { font-size: 9px; line-height: 1.25; margin-top: 3px; }
        .document-title { border-top: 3px solid #111; border-bottom: 3px solid #111; text-align: center; font-size: 16px; font-weight: bold; padding: 4px 0 5px; margin: 8px 0 14px; }
        .meta { margin-bottom: 17px; }
        .meta td { vertical-align: top; padding: 2px 0; }
        .meta .left { width: 50%; padding: 2px 20px 2px 0; }
        .meta .right { width: 50%; padding: 2px 0 2px 18px; }
        .meta-field { width: 100%; table-layout: fixed; }
        .meta-field td { padding: 0; vertical-align: top; }
        .meta-field .field-label { width: 98px; white-space: nowrap; }
        .meta-field .field-label-right { width: 108px; white-space: nowrap; }
        .meta-field .field-value { width: auto; word-wrap: break-word; overflow-wrap: anywhere; }
        .meta-column { width: 100%; table-layout: fixed; }
        .meta-column > tbody > tr > td { padding: 2px 0; vertical-align: top; }
        .meta-value { font-weight: normal; }
        .meta-value strong { font-weight: bold; }
        .address { min-height: 33px; }
        .items { border: 1px solid #111; table-layout: fixed; }
        .items th, .items td { border: 1px solid #111; }
        .items th { background: #cfcfcf; text-align: center; font-size: 9px; padding: 6px 3px; height: 31px; }
        .items td { padding: 7px 6px; vertical-align: middle; height: 62px; }
        .items .no { width: 6%; text-align: center; }
        .items .size { width: 11%; }
        .items .description { width: 32%; }
        .items .qty { width: 13%; text-align: center; }
        .items .uom { width: 10%; text-align: center; }
        .items .price { width: 14%; text-align: right; }
        .items .total { width: 14%; text-align: right; }
        .item-description { white-space: normal; }
        .total-row td { height: 16px; padding: 2px 6px; font-weight: bold; }
        .total-label { text-align: center; }
        .total-value { text-align: right; }
        .notes { margin: 15px 0 0 15px; min-height: 90px; }
        .notes-title { font-weight: bold; text-decoration: underline; }
        .notes-body { margin: 7px 0 0 18px; }
        .notes-body div { margin-bottom: 4px; }
        .signature-table { margin-top: 55px; table-layout: fixed; }
        .signature-table td { width: 33.333%; text-align: center; vertical-align: top; }
        .signature-label { height: 22px; }
        .signature-area { height: 84px; position: relative; }
        .signature-image { max-height: 57px; max-width: 145px; position: relative; z-index: 2; margin-top: 13px; }
        .stamp-image { max-height: 65px; max-width: 120px; position: absolute; left: 50%; top: 6px; transform: translateX(-50%); opacity: .75; z-index: 1; }
        .name-line { display: inline-block; min-width: 120px; border-bottom: 1px solid #111; font-weight: bold; text-decoration: underline; padding: 0 3px 2px; }
        .blank-line { display: inline-block; width: 160px; border-bottom: 1px solid #111; height: 22px; }
    </style>
</head>
<body>
<?php
    $formatDate = static function ($date) {
        if (! $date) return '-';
        $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $time = strtotime((string) $date);
        return $time ? date('d', $time) . ' ' . ($months[(int) date('n', $time)] ?? date('F', $time)) . ' ' . date('Y', $time) : (string) $date;
    };
    $vendorName = $vendor['name'] ?? $po['vendor_name'] ?? '-';
    $vendorAddress = trim((string) ($vendor['address'] ?? '')) ?: '-';
    $vendorContact = trim((string) ($vendor['pic_name'] ?? ''));
    $vendorPhone = trim((string) ($vendor['phone'] ?? ''));
    $vendorEmail = trim((string) ($vendor['email'] ?? ''));
    $attn = $vendorContact ?: '-';
    $attnContact = trim(implode(' ', array_filter([$vendorPhone, $vendorEmail])));
    $clientPoNumber = $clientPo['po_no'] ?? $po['client_po_no'] ?? '-';
    $preparedBy = trim((string) ($po['created_by_name'] ?? '')) ?: '-';
    $approvedBy = trim((string) ($settings['signer_name'] ?? ''));
?>
<table class="header">
    <tr>
        <td class="logo-cell"><?php if (! empty($logoData)): ?><img src="<?= $logoData ?>" class="logo" alt="Logo perusahaan"><?php endif; ?></td>
        <td class="company-cell">
            <div class="company-name"><?= esc(strtoupper($settings['company_name'] ?? '')) ?></div>
            <div class="company-info">
                <?= esc($settings['office_1'] ?? '') ?><?= ! empty($settings['office_2']) ? ', ' . esc($settings['office_2']) : '' ?><br>
                Telp: <?= esc($settings['phone'] ?? '') ?><?= ! empty($settings['email']) ? ', e-mail: ' . esc($settings['email']) : '' ?><br>
                <?= ! empty($settings['tax_id']) ? 'NPWP : ' . esc($settings['tax_id']) : '' ?>
            </div>
        </td>
    </tr>
</table>

<div class="document-title"><?= esc(strtoupper($settings['document_title'] ?? 'PURCHASE ORDER')) ?></div>

<table class="meta">
    <tr>
        <td class="left">
            <table class="meta-column">
                <tr><td><table class="meta-field"><tr><td class="field-label">To :</td><td class="field-value"><strong><?= esc($vendorName) ?></strong></td></tr></table></td></tr>
                <tr><td class="address"><table class="meta-field"><tr><td class="field-label">Address :</td><td class="field-value"><?= nl2br(esc($vendorAddress)) ?></td></tr></table></td></tr>
                <tr><td><table class="meta-field"><tr><td class="field-label">Attn/email :</td><td class="field-value"><?= esc($attn) ?><?php if ($attnContact): ?><br><?= esc($attnContact) ?><?php endif; ?></td></tr></table></td></tr>
            </table>
        </td>
        <td class="right">
            <table class="meta-column">
                <tr><td><table class="meta-field"><tr><td class="field-label-right"><strong>P.O No :</strong></td><td class="field-value"><strong><?= esc($po['po_no']) ?></strong></td></tr></table></td></tr>
                <tr><td><table class="meta-field"><tr><td class="field-label-right">Date :</td><td class="field-value"><?= esc($formatDate($po['po_date'] ?? null)) ?></td></tr></table></td></tr>
                <tr><td><table class="meta-field"><tr><td class="field-label-right">Delivery Time :</td><td class="field-value"><?= esc($formatDate($po['expected_date'] ?? null)) ?></td></tr></table></td></tr>
                <tr><td><table class="meta-field"><tr><td class="field-label-right">Shipping To :</td><td class="field-value"><?= nl2br(esc($vendorAddress)) ?></td></tr></table></td></tr>
                <tr><td><table class="meta-field"><tr><td class="field-label-right">Quot Number :</td><td class="field-value"><?= esc($clientPoNumber) ?></td></tr></table></td></tr>
                <tr><td><table class="meta-field"><tr><td class="field-label-right">TOP :</td><td class="field-value"><?= esc($settings['default_payment_terms'] ?? '-') ?></td></tr></table></td></tr>
                <tr><td><table class="meta-field"><tr><td class="field-label-right">System Payment :</td><td class="field-value">Transfer</td></tr></table></td></tr>
            </table>
        </td>
    </tr>
</table>

<table class="items">
    <thead>
        <tr><th class="no">No</th><th class="size">SIZE</th><th class="description">DESCRIPTION</th><th class="qty">QTY</th><th class="uom">UoM</th><th class="price">Unit<br>Price (Rp)</th><th class="total">Total<br>Price (Rp)</th></tr>
    </thead>
    <tbody>
    <?php foreach (($po['items'] ?? []) as $index => $item): ?>
        <tr>
            <td class="no"><?= $index + 1 ?></td>
            <td class="size"><?= esc($item['size'] ?? '-') ?></td>
            <td class="description item-description"><strong><?= esc($item['product_name'] ?? '-') ?></strong><?php if (! empty($item['description'])): ?><br><?= nl2br(esc($item['description'])) ?><?php endif; ?></td>
            <td class="qty"><?= esc(number_format((float) ($item['quantity'] ?? 0), 3, '.', ',')) ?></td>
            <td class="uom"><?= esc($item['unit'] ?? 'pcs') ?></td>
            <td class="price"><?= number_format((float) ($item['unit_price'] ?? 0), 0, ',', '.') ?></td>
            <td class="total"><?= number_format((float) ($item['line_total'] ?? 0), 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="total-row"><td colspan="5"></td><td class="total-label">TOTAL</td><td class="total-value"><?= number_format((float) ($po['grand_total'] ?? 0), 0, ',', '.') ?></td></tr>
    </tfoot>
</table>

<div class="notes">
    <div class="notes-title">NOTES</div>
    <div class="notes-body">
        <div>• Harga sudah termasuk PPN <?= esc($po['ppn_percent'] ?? $settings['default_tax_percent'] ?? 0) ?>%</div>
        <?php if (! empty($po['notes'])): ?><div>• <?= nl2br(esc($po['notes'])) ?></div><?php endif; ?>
        <?php if (! empty($settings['default_delivery_terms'])): ?><div>• <?= esc($settings['default_delivery_terms']) ?></div><?php endif; ?>
    </div>
</div>

<table class="signature-table">
    <tr>
        <td><div class="signature-label">Prepared by,</div><div class="signature-area"><?php if (! empty($preparedSignatureData)): ?><img src="<?= $preparedSignatureData ?>" class="signature-image" alt="Tanda tangan Prepared by"><?php endif; ?></div><span class="name-line"><?= esc($preparedBy) ?></span></td>
        <td><div class="signature-label">Approved by,</div><div class="signature-area"><?php if (! empty($signatureData)): ?><img src="<?= $signatureData ?>" class="signature-image" alt="Tanda tangan Approved by"><?php endif; ?><?php if (! empty($stampData)): ?><img src="<?= $stampData ?>" class="stamp-image" alt="Stempel Approved by"><?php endif; ?></div><span class="name-line"><?= esc($approvedBy) ?></span></td>
        <td><div class="signature-label">Supplier</div><div class="signature-area"></div><span class="blank-line"></span></td>
    </tr>
</table>
</body>
</html>
