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
        .product { page-break-inside: avoid; border: 1px solid #cbd5e1; margin-bottom: 13px; padding: 10px; }
        .product-table td { vertical-align: top; }
        .product-image-cell { width: 125px; text-align: center; padding-right: 10px; }
        .product-image { max-width: 112px; max-height: 105px; }
        .muted { color: #64748b; }
        .label { font-weight: bold; color: #475569; }
        ul { margin: 3px 0 0 15px; padding: 0; }
        li { margin-bottom: 2px; }
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
        $specs = $product ? (json_decode($product['technical_specs'] ?? '', true) ?: []) : [];
    ?>
    <div class="product">
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
                    <h2><?= $index + 1 ?>. <?= esc($product['name'] ?? $item['product_name']) ?></h2>
                    <?php if (!empty($product['brand']) || !empty($product['sku'])): ?>
                    <p class="muted"><?= esc($product['brand'] ?? '') ?><?= !empty($product['brand']) && !empty($product['sku']) ? ' · ' : '' ?><?= esc($product['sku'] ?? '') ?></p>
                    <?php endif; ?>
                    <?php if (!empty($product['description']) || !empty($item['description'])): ?>
                    <p><?= nl2br(esc($product['description'] ?? $item['description'])) ?></p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <?php if ($specs): ?>
        <h3>Spesifikasi Teknis</h3>
        <ul>
            <?php foreach ($specs as $key => $value): ?><li><span class="label"><?= esc(ucwords(str_replace(['_', '-'], ' ', (string) $key))) ?>:</span> <?= esc(is_scalar($value) ? (string) $value : json_encode($value)) ?></li><?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <?php if (!empty($product['applications'])): ?><p><span class="label">Aplikasi:</span> <?= nl2br(esc($product['applications'])) ?></p><?php endif; ?>
        <?php if (!empty($product['standards'])): ?><p><span class="label">Standar:</span> <?= nl2br(esc($product['standards'])) ?></p><?php endif; ?>
        <?php if (!empty($product['datasheet'])): ?><p><span class="label">Datasheet:</span> <?= nl2br(esc($product['datasheet'])) ?></p><?php endif; ?>
        <?php if (!empty($product['datasheet_file_path'])): ?><p><span class="label">File datasheet:</span> <a href="<?= esc(base_url(ltrim($product['datasheet_file_path'], '/'))) ?>">Unduh datasheet PDF</a></p><?php endif; ?>
    </div>
    <?php endforeach; ?>

    <div class="footer">Dokumen ini dibuat sebagai lampiran pendukung quotation <?= esc($quotation['quotation_no']) ?>.</div>
</body>
</html>
