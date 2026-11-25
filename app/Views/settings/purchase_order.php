<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$assetUrl = static function ($path) {
    if (!$path) return null;
    return preg_match('#^https?://#i', $path) ? $path : base_url(ltrim($path, '/'));
};
?>
<div class="app-content-header">
    <div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1"><i class="bi bi-cart-check me-2"></i>Setting Purchase Order</h2>
            <p class="text-body-secondary mb-0">Atur data utama dan tampilan dokumen PO OUT yang dicetak ke vendor.</p>
        </div>
    </div>
</div>
<form method="post" action="<?= site_url('settings/purchase-order') ?>" enctype="multipart/form-data" class="form-card">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-building me-2"></i>Identitas Perusahaan</h3></div>
                <div class="card-body"><div class="row g-3">
                    <div class="col-12"><label class="field-label">Nama perusahaan *</label><input required name="company_name" class="form-control" value="<?= old('company_name', $settings['company_name'] ?? '') ?>"></div>
                    <div class="col-12"><label class="field-label">Office 1</label><input name="office_1" class="form-control" value="<?= old('office_1', $settings['office_1'] ?? '') ?>"></div>
                    <div class="col-12"><label class="field-label">Office 2</label><input name="office_2" class="form-control" value="<?= old('office_2', $settings['office_2'] ?? '') ?>"></div>
                    <div class="col-md-6"><label class="field-label">Telepon</label><input name="phone" class="form-control" value="<?= old('phone', $settings['phone'] ?? '') ?>"></div>
                    <div class="col-md-6"><label class="field-label">Email</label><input type="email" name="email" class="form-control" value="<?= old('email', $settings['email'] ?? '') ?>"></div>
                    <div class="col-md-6"><label class="field-label">NPWP</label><input name="tax_id" class="form-control" value="<?= old('tax_id', $settings['tax_id'] ?? '') ?>"></div>
                </div></div>
            </div>
            <div class="card card-secondary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-file-earmark-text me-2"></i>Data Utama & Default PO</h3></div>
                <div class="card-body"><div class="row g-3">
                    <div class="col-md-8"><label class="field-label">Judul dokumen</label><input name="document_title" class="form-control" value="<?= old('document_title', $settings['document_title'] ?? 'PURCHASE ORDER') ?>" placeholder="PURCHASE ORDER"></div>
                    <div class="col-md-4"><label class="field-label">PPN default (%)</label><input type="number" step="0.01" min="0" max="100" name="default_tax_percent" class="form-control" value="<?= old('default_tax_percent', $settings['default_tax_percent'] ?? 11) ?>"></div>
                    <div class="col-md-4"><label class="field-label">PPh default (%)</label><input type="number" step="0.01" min="0" max="100" name="default_pph_percent" class="form-control" value="<?= old('default_pph_percent', $settings['default_pph_percent'] ?? 0) ?>"></div>
                    <div class="col-md-8"><label class="field-label">Nama penandatangan</label><input name="signer_name" class="form-control" value="<?= old('signer_name', $settings['signer_name'] ?? '') ?>"></div>
                    <div class="col-md-6"><label class="field-label">Syarat pembayaran</label><input name="default_payment_terms" class="form-control" value="<?= old('default_payment_terms', $settings['default_payment_terms'] ?? '') ?>" placeholder="Transfer 30 hari setelah invoice"></div>
                    <div class="col-md-6"><label class="field-label">Syarat pengiriman</label><input name="default_delivery_terms" class="form-control" value="<?= old('default_delivery_terms', $settings['default_delivery_terms'] ?? '') ?>" placeholder="Sesuai jadwal PO"></div>
                    <div class="col-md-6"><label class="field-label">Telepon penandatangan</label><input name="signer_phone" class="form-control" value="<?= old('signer_phone', $settings['signer_phone'] ?? '') ?>"></div>
                </div></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-warning card-outline"><div class="card-header"><h3 class="card-title"><i class="bi bi-images me-2"></i>Branding Dokumen</h3></div><div class="card-body">
                <?php foreach ([
                    'logo' => ['Logo perusahaan', 'logo_path'],
                    'signature' => ['Tanda tangan Approved by', 'signature_path'],
                    'stamp' => ['Stempel Approved by', 'stamp_path'],
                ] as $field => [$label, $column]): $url = $assetUrl($settings[$column] ?? null); ?>
                    <label class="field-label"><?= $label ?></label><input type="file" name="<?= $field ?>" accept="image/png,image/jpeg,image/webp" class="form-control mb-2">
                    <?php if ($url): ?><img src="<?= esc($url) ?>" class="img-thumbnail mb-3" style="max-height:100px" alt="<?= esc($label) ?> saat ini"><?php endif; ?>
                <?php endforeach; ?>
                <div class="form-text mt-2">Format JPG, PNG, atau WEBP. Maksimal 5 MB per file.</div>
            </div></div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check2-circle me-1"></i>Simpan Setting PO</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
