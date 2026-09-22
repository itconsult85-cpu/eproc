<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 32px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 10px; }
        h1 { margin: 0 0 4px; font-size: 20px; color: #0f3d67; }
        h2 { margin: 0 0 3px; font-size: 14px; color: #0f3d67; }
        h3 { margin: 12px 0 4px; font-size: 11px; color: #0f3d67; }
        p { margin: 3px 0; line-height: 1.35; }
        .header { border-bottom: 2px solid #0f3d67; padding-bottom: 9px; margin-bottom: 14px; }
        .header-table, .product-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: top; }
        .logo { max-width: 135px; max-height: 50px; }
        .meta { text-align: right; color: #4b5563; }
        .product { page-break-inside: avoid; border: 1px solid #cbd5e1; margin-bottom: 13px; padding: 0 10px 10px; }
        .product + .product { page-break-before: always; }
        .product-banner { background: #d71920; color: #fff; margin: 0 -10px 10px; padding: 9px 12px; }
        .product-banner h2 { color: #fff; margin: 0; font-size: 18px; }
        .product-banner p { margin: 2px 0 0; font-size: 9px; }
        .product-table td { vertical-align: top; }
        .product-image-cell { width: 125px; text-align: center; padding-right: 10px; }
        .product-image { max-width: 112px; max-height: 105px; }
        .muted { color: #64748b; }
        .label { font-weight: bold; color: #475569; }
        .spec-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .spec-table td { border: 1px solid #b8c1cc; padding: 4px 6px; vertical-align: top; }
        .spec-table td:first-child { width: 30%; background: #edf0f3; font-weight: bold; color: #374151; }
        .footer { margin-top: 14px; padding-top: 6px; border-top: 1px solid #cbd5e1; color: #64748b; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <?php if ($logoData): ?><img src="<?= $logoData ?>" class="logo" alt="Logo"><br><?php endif; ?>
                    <h1>Katalog Produk Pendukung Quotation</h1>
                    <p class="muted"><?= esc($settings['company_name'] ?? '') ?></p>
                </td>
                <td class="meta">
                    <strong>No. Quotation</strong><br><?= esc($quotation['quotation_no']) ?><br>
                    <strong>Tanggal</strong><br><?= esc($quotation['issue_date'] ?? '-') ?><br>
                    <strong>Customer</strong><br><?= esc($quotation['customer_name'] ?: $quotation['company_name']) ?>
                </td>
            </tr>
        </table>
    </div>

    <?php if (!$items): ?>
    <p>Tidak ada produk yang dipilih pada quotation ini.</p>
    <?php endif; ?>

    <?php foreach ($items as $index => $entry):
        $product = $entry['product'] ?? null;
        $item = $entry['item'];
        $rawSpecs = $product ? (json_decode($product['technical_specs'] ?? '', true) ?: []) : [];
        if (is_array($rawSpecs) && (array_key_exists('label', $rawSpecs) || array_key_exists('value', $rawSpecs))) {
            $rawSpecs = [$rawSpecs];
        }
        $specs = [];
        foreach ((array) $rawSpecs as $key => $spec) {
            if (is_array($spec) && (array_key_exists('label', $spec) || array_key_exists('value', $spec))) {
                $label = trim((string) ($spec['label'] ?? ''));
                $value = trim((string) ($spec['value'] ?? ''));
            } else {
                $label = trim((string) $key);
                $value = is_scalar($spec) ? trim((string) $spec) : trim((string) json_encode($spec));
            }
            if ($label !== '' && $value !== '') {
                $specs[] = ['label' => $label, 'value' => $value];
            }
        }
    ?>
    <div class="product">
        <div class="product-banner">
            <h2><?= esc($product['name'] ?? $item['product_name']) ?></h2>
            <p><?= esc($product['brand'] ?? '') ?><?= !empty($product['brand']) && !empty($product['sku']) ? ' · ' : '' ?><?= esc($product['sku'] ?? '') ?></p>
        </div>
        <table class="product-table">
            <tr>
                <td class="product-image-cell">
                    <?php if (!empty($entry['imageData'])): ?>
                    <img src="<?= $entry['imageData'] ?>" class="product-image" alt="Produk">
                    <?php else: ?>
                    <span class="muted">Tidak ada gambar</span>
                    <?php endif; ?>
                </td>
                <td>
                    <p><span class="label">Product No.:</span> <?= $index + 1 ?></p>
                    <?php if (!empty($product['description']) || !empty($item['description'])): ?>
                    <p><?= nl2br(esc($product['description'] ?? $item['description'])) ?></p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <?php if ($specs): ?>
        <h3>Spesifikasi Teknis</h3>
        <table class="spec-table">
            <?php foreach ($specs as $spec): ?><tr><td><?= esc($spec['label']) ?></td><td><?= nl2br(esc($spec['value'])) ?></td></tr><?php endforeach; ?>
        </table>
        <?php endif; ?>
        <?php if (!empty($product['applications']) || !empty($product['standards']) || !empty($product['datasheet'])): ?>
        <h3>Informasi Produk</h3>
        <table class="spec-table">
            <?php if (!empty($product['applications'])): ?><tr><td>Aplikasi / Fungsi</td><td><?= nl2br(esc($product['applications'])) ?></td></tr><?php endif; ?>
            <?php if (!empty($product['standards'])): ?><tr><td>Standar / Sertifikasi</td><td><?= nl2br(esc($product['standards'])) ?></td></tr><?php endif; ?>
            <?php if (!empty($product['datasheet'])): ?><tr><td>Catatan Datasheet</td><td><?= nl2br(esc($product['datasheet'])) ?></td></tr><?php endif; ?>
        </table>
        <?php endif; ?>
        <?php if (!empty($product['datasheet_file_path'])): ?><p><span class="label">File datasheet:</span> <a href="<?= esc(base_url(ltrim($product['datasheet_file_path'], '/'))) ?>">Unduh datasheet PDF</a></p><?php endif; ?>
    </div>
    <?php endforeach; ?>

    <div class="footer">Dokumen ini dibuat sebagai lampiran pendukung quotation <?= esc($quotation['quotation_no']) ?>.</div>
</body>
</html>
