<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h4 mb-1">Preview Katalog Produk</h1>
        <p class="text-body-secondary mb-0">Quotation <?= esc($quotation['quotation_no']) ?> · <?= count($items) ?> produk terpilih</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= esc(site_url('quotations/' . $quotation['id'])) ?>" class="btn btn-outline-secondary">Kembali</a>
        <a href="<?= esc(site_url('quotations/' . $quotation['id'] . '/catalog')) ?>" class="btn btn-success"><i class="bi bi-download me-1"></i>Unduh PDF Katalog</a>
    </div>
</div>
<div class="alert alert-info">
    Periksa isi katalog di bawah ini. PDF baru akan diunduh setelah tombol <strong>Unduh PDF Katalog</strong> ditekan.
</div>
<div class="card">
    <div class="card-body">
        <div class="border-bottom pb-3 mb-3">
            <?php if ($logoData): ?><img src="<?= $logoData ?>" alt="Logo" style="max-width:180px;max-height:60px;object-fit:contain" class="mb-2"><br><?php endif; ?>
            <h2 class="h4 text-primary mb-1">Katalog Produk Pendukung Quotation</h2>
            <div class="text-body-secondary"><?= esc($settings['company_name'] ?? '') ?> · Customer: <?= esc($quotation['customer_name'] ?: $quotation['company_name']) ?></div>
        </div>
        <?php if (!$items): ?>
        <p class="text-body-secondary">Tidak ada produk yang dipilih pada quotation ini.</p>
        <?php endif; ?>
        <div class="row g-3">
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
            <div class="col-12">
                <article class="border rounded p-3 h-100">
                    <div class="row g-3">
                        <div class="col-sm-3 col-md-2 text-center">
                            <?php if (!empty($entry['imageData'])): ?>
                            <img src="<?= $entry['imageData'] ?>" alt="Produk" class="img-fluid rounded" style="max-height:140px;object-fit:contain">
                            <?php else: ?><span class="text-body-secondary small">Tidak ada gambar</span><?php endif; ?>
                        </div>
                        <div class="col-sm-9 col-md-10">
                            <h2 class="h5 mb-1"><?= $index + 1 ?>. <?= esc($product['name'] ?? $item['product_name']) ?></h2>
                            <?php if (!empty($product['brand']) || !empty($product['sku'])): ?><div class="text-body-secondary small mb-2"><?= esc($product['brand'] ?? '') ?><?= !empty($product['brand']) && !empty($product['sku']) ? ' · ' : '' ?><?= esc($product['sku'] ?? '') ?></div><?php endif; ?>
                            <?php if (!empty($product['description']) || !empty($item['description'])): ?><p class="mb-2"><?= nl2br(esc($product['description'] ?? $item['description'])) ?></p><?php endif; ?>
                            <?php if ($specs): ?>
                            <div class="fw-semibold">Spesifikasi Teknis</div>
                            <ul class="mb-2">
                                <?php foreach ($specs as $spec): ?><li><strong><?= esc($spec['label']) ?>:</strong> <?= nl2br(esc($spec['value'])) ?></li><?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                            <?php if (!empty($product['applications'])): ?><p class="mb-1"><strong>Aplikasi:</strong> <?= nl2br(esc($product['applications'])) ?></p><?php endif; ?>
                            <?php if (!empty($product['standards'])): ?><p class="mb-1"><strong>Standar:</strong> <?= nl2br(esc($product['standards'])) ?></p><?php endif; ?>
                            <?php if (!empty($product['datasheet'])): ?><p class="mb-1"><strong>Datasheet:</strong> <?= nl2br(esc($product['datasheet'])) ?></p><?php endif; ?>
                            <?php if (!empty($product['datasheet_file_path'])): ?><a href="<?= esc(base_url(ltrim($product['datasheet_file_path'], '/'))) ?>" target="_blank" rel="noopener"><i class="bi bi-file-earmark-pdf me-1"></i>Lihat file datasheet</a><?php endif; ?>
                        </div>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
