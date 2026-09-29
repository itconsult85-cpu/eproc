<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$rawSpecs = json_decode($product['technical_specs'] ?? '', true) ?: [];
if (is_array($rawSpecs) && (array_key_exists('label', $rawSpecs) || array_key_exists('value', $rawSpecs))) $rawSpecs = [$rawSpecs];
$specs = [];
foreach ((array) $rawSpecs as $key => $spec) {
    $label = is_array($spec) ? trim((string) ($spec['label'] ?? $key)) : trim((string) $key);
    $value = is_array($spec) ? trim((string) ($spec['value'] ?? '')) : (is_scalar($spec) ? trim((string) $spec) : trim((string) json_encode($spec)));
    if ($label !== '' && $value !== '') $specs[] = ['label' => $label, 'value' => $value];
}
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div><h1 class="h4 mb-1">Preview Katalog Produk</h1><p class="text-body-secondary mb-0">Periksa hasil katalog untuk <?= esc($product['name']) ?> sebelum diunduh.</p></div>
    <div class="d-flex flex-wrap gap-2"><a href="<?= esc(site_url('products')) ?>" class="btn btn-outline-secondary">Kembali</a><a href="<?= esc(site_url('products/' . public_id((int) $product['id']) . '/catalog')) ?>" class="btn btn-success"><i class="bi bi-download me-1"></i>Unduh PDF</a></div>
</div>
<div class="alert alert-info">Katalog ini dibuat dari data produk yang tersimpan saat ini.</div>
<div class="card"><div class="card-body">
    <?php if (! empty($settings['logo_path'])): ?><img src="<?= esc(base_url(ltrim($settings['logo_path'], '/'))) ?>" alt="Logo" style="max-width:180px;max-height:60px;object-fit:contain" class="mb-3"> <?php endif; ?>
    <div class="border-bottom pb-3 mb-3"><h2 class="h3 text-primary mb-1"><?= esc($product['name']) ?></h2><div class="text-body-secondary"><?= esc(trim(($product['brand'] ?? '') . (!empty($product['brand']) && !empty($product['sku']) ? ' · ' : '') . ($product['sku'] ?? ''))) ?></div></div>
    <div class="row g-4"><div class="col-md-3 text-center"><?php if ($imageData): ?><img src="<?= $imageData ?>" alt="<?= esc($product['name']) ?>" class="img-fluid rounded border" style="max-height:240px;object-fit:contain"><?php else: ?><div class="text-body-secondary py-5"><i class="bi bi-image fs-1 d-block"></i>Tidak ada gambar</div><?php endif; ?></div>
    <div class="col-md-9"><p><?= nl2br(esc($product['description'] ?? '')) ?></p><?php if ($specs): ?><h3 class="h6 mt-3">Spesifikasi Teknis</h3><div class="table-responsive"><table class="table table-sm table-bordered"><tbody><?php foreach ($specs as $spec): ?><tr><th class="table-light" style="width:30%"><?= esc($spec['label']) ?></th><td><?= nl2br(esc($spec['value'])) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div></div>
    <?php if (!empty($product['applications']) || !empty($product['standards']) || !empty($product['datasheet'])): ?><hr><h3 class="h6">Informasi Produk</h3><dl class="row mb-0"><?php if (!empty($product['applications'])): ?><dt class="col-sm-3">Aplikasi / Fungsi</dt><dd class="col-sm-9"><?= nl2br(esc($product['applications'])) ?></dd><?php endif; ?><?php if (!empty($product['standards'])): ?><dt class="col-sm-3">Standar / Sertifikasi</dt><dd class="col-sm-9"><?= nl2br(esc($product['standards'])) ?></dd><?php endif; ?><?php if (!empty($product['datasheet'])): ?><dt class="col-sm-3">Catatan Datasheet</dt><dd class="col-sm-9"><?= nl2br(esc($product['datasheet'])) ?></dd><?php endif; ?></dl><?php endif; ?>
    <?php if (!empty($product['datasheet_file_path'])): ?><hr><a href="<?= esc(base_url(ltrim($product['datasheet_file_path'], '/'))) ?>" target="_blank" rel="noopener"><i class="bi bi-file-earmark-pdf me-1"></i>Lihat datasheet PDF</a><?php endif; ?>
</div></div>
<?= $this->endSection() ?>
