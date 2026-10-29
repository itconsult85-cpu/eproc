<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>PO OUT <?= esc($po['po_no']) ?></title>
    <style>
        @page { margin: 28px 34px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #202124; font-size: 10px; line-height: 1.4; }
        table { width: 100%; border-collapse: collapse; }
        .header { border-bottom: 3px double #222; padding-bottom: 8px; margin-bottom: 14px; }
        .company-name { font-size: 18px; font-weight: bold; text-transform: uppercase; }
        .company-info { color: #444; font-size: 9px; line-height: 1.35; }
        .document-title { text-align: center; font-size: 17px; font-weight: bold; margin: 12px 0 14px; text-transform: uppercase; }
        .meta { margin-bottom: 16px; }
        .meta td { padding: 3px 0; vertical-align: top; }
        .meta .label { width: 125px; font-weight: bold; }
        .meta .colon { width: 12px; }
        .vendor-box { border: 1px solid #777; padding: 8px; margin: 8px 0 15px; }
        .vendor-box .label { color: #555; font-size: 9px; text-transform: uppercase; }
        .items th, .items td { border: 1px solid #555; padding: 6px 5px; vertical-align: top; }
        .items th { background: #e9ecef; text-align: center; font-size: 9px; }
        .center { text-align: center; }
        .right { text-align: right; white-space: nowrap; }
        .summary { width: 48%; margin-left: auto; margin-top: 10px; }
        .summary td { padding: 3px 5px; }
        .summary .total td { border-top: 1px solid #222; font-weight: bold; font-size: 11px; padding-top: 6px; }
        .notes { margin-top: 15px; }
        .signatures { margin-top: 38px; }
        .signatures td { width: 50%; text-align: center; vertical-align: top; }
        .signature-space { height: 58px; }
        .signature-line { display: inline-block; min-width: 170px; border-bottom: 1px solid #222; font-weight: bold; }
        .footer { margin-top: 22px; color: #666; font-size: 8px; text-align: right; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <div class="company-name"><?= esc($settings['company_name'] ?? 'EPROCUREMENT') ?></div>
                <div class="company-info">
                    <?= esc($settings['office_1'] ?? '') ?><?= ! empty($settings['office_2']) ? ' · ' . esc($settings['office_2']) : '' ?><br>
                    <?= ! empty($settings['phone']) ? 'Telp. ' . esc($settings['phone']) : '' ?><?= ! empty($settings['email']) ? ' · ' . esc($settings['email']) : '' ?><br>
                    <?= ! empty($settings['tax_id']) ? 'NPWP: ' . esc($settings['tax_id']) : '' ?>
                </div>
            </td>
            <td style="width: 180px; text-align: right; vertical-align: top;">
                <strong>PO OUT VENDOR</strong><br>
                <?= esc($po['po_no']) ?>
            </td>
        </tr>
    </table>

    <div class="document-title">Purchase Order</div>

    <table class="meta">
        <tr><td class="label">Nomor PO</td><td class="colon">:</td><td><?= esc($po['po_no']) ?></td><td class="label">Tanggal</td><td class="colon">:</td><td><?= esc($po['po_date'] ?? '-') ?></td></tr>
        <tr><td class="label">Perkiraan Tiba</td><td class="colon">:</td><td><?= esc($po['expected_date'] ?? '-') ?></td><td class="label">Status</td><td class="colon">:</td><td><?= esc(ucfirst((string) ($po['status'] ?? 'draft'))) ?></td></tr>
        <?php if ($clientPo): ?><tr><td class="label">Referensi PO IN</td><td class="colon">:</td><td colspan="4"><?= esc($clientPo['po_no'] ?? $po['client_po_no'] ?? '-') ?><?= ! empty($clientPo['po_date']) ? ' · ' . esc($clientPo['po_date']) : '' ?></td></tr><?php endif; ?>
    </table>

    <div class="vendor-box">
        <div class="label">Kepada Vendor</div>
        <strong><?= esc($vendor['name'] ?? $po['vendor_name'] ?? '-') ?></strong><br>
        <?= nl2br(esc($vendor['address'] ?? '')) ?>
        <?php if (! empty($vendor['phone']) || ! empty($vendor['email'])): ?><br><?= esc($vendor['phone'] ?? '') ?><?= ! empty($vendor['email']) ? ' · ' . esc($vendor['email']) : '' ?><?php endif; ?>
        <?php if (! empty($vendor['pic_name'])): ?><br>Attn: <?= esc($vendor['pic_name']) ?><?php endif; ?>
    </div>

    <table class="items">
        <thead><tr><th style="width: 28px">No.</th><th>Produk</th><th>Deskripsi</th><th style="width: 55px">Qty</th><th style="width: 55px">Satuan</th><th style="width: 100px">Harga</th><th style="width: 105px">Jumlah</th></tr></thead>
        <tbody>
            <?php foreach (($po['items'] ?? []) as $index => $item): ?>
                <tr>
                    <td class="center"><?= $index + 1 ?></td>
                    <td><?= esc($item['product_name'] ?? '-') ?></td>
                    <td><?= nl2br(esc($item['description'] ?? '-')) ?></td>
                    <td class="right"><?= esc($item['quantity'] ?? 0) ?></td>
                    <td class="center"><?= esc($item['unit'] ?? 'pcs') ?></td>
                    <td class="right">Rp <?= number_format((float) ($item['unit_price'] ?? 0), 0, ',', '.') ?></td>
                    <td class="right">Rp <?= number_format((float) ($item['line_total'] ?? 0), 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="summary">
        <tr><td>Subtotal</td><td class="right">Rp <?= number_format((float) ($po['subtotal'] ?? 0), 0, ',', '.') ?></td></tr>
        <?php if (! empty($po['use_ppn'])): ?><tr><td>PPN (<?= esc($po['ppn_percent'] ?? 0) ?>%)</td><td class="right">Rp <?= number_format((float) ($po['tax_amount'] ?? 0), 0, ',', '.') ?></td></tr><?php endif; ?>
        <?php if (! empty($po['use_pph'])): ?><tr><td>PPh (<?= esc($po['pph_percent'] ?? 0) ?>%)</td><td class="right">- Rp <?= number_format((float) ($po['pph_amount'] ?? 0), 0, ',', '.') ?></td></tr><?php endif; ?>
        <tr class="total"><td>Total PO</td><td class="right">Rp <?= number_format((float) ($po['grand_total'] ?? 0), 0, ',', '.') ?></td></tr>
    </table>

    <?php if (! empty($po['notes'])): ?><div class="notes"><strong>Catatan:</strong><br><?= nl2br(esc($po['notes'])) ?></div><?php endif; ?>

    <table class="signatures">
        <tr>
            <td>Dibuat oleh,<div class="signature-space"></div><span class="signature-line"><?= esc(auth_user('full_name') ?: auth_user('username') ?: 'Procurement') ?></span></td>
            <td>Disetujui oleh,<div class="signature-space"></div><span class="signature-line">&nbsp;</span></td>
        </tr>
    </table>
    <div class="footer">Dokumen dibuat dari sistem EPROC · Dicetak <?= date('d-m-Y H:i') ?></div>
</body>
</html>
