<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$settingsLogo = $settings['logo_path'] ?? null;
$settingsLogoUrl = $settingsLogo
    ? (preg_match('#^https?://#i', $settingsLogo) ? $settingsLogo : base_url(ltrim($settingsLogo, '/')))
    : null;
$settingsSignature = $settings['signature_path'] ?? null;
$settingsSignatureUrl = $settingsSignature
    ? (preg_match('#^https?://#i', $settingsSignature) ? $settingsSignature : base_url(ltrim($settingsSignature, '/')))
    : null;
$settingsStamp = $settings['stamp_path'] ?? null;
$settingsStampUrl = $settingsStamp
    ? (preg_match('#^https?://#i', $settingsStamp) ? $settingsStamp : base_url(ltrim($settingsStamp, '/')))
    : null;
?>
<form method="post" action="/settings/quotation" enctype="multipart/form-data" class="form-card">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-building me-2"></i>Identitas Perusahaan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="field-label">
                                Nama perusahaan *
                            </label>
                            <input required name="company_name" class="form-control" placeholder="Nama legal perusahaan"
                                value="<?= old('company_name', $settings['company_name'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="field-label">
                                Office 1
                            </label>
                            <input name="office_1" class="form-control" placeholder="Alamat kantor utama"
                                value="<?= old('office_1', $settings['office_1'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="field-label">
                                Office 2
                            </label>
                            <input name="office_2" class="form-control" placeholder="Alamat kantor/cabang kedua"
                                value="<?= old('office_2', $settings['office_2'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Telepon
                            </label>
                            <input name="phone" class="form-control"
                                value="<?= old('phone', $settings['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Email
                            </label>
                            <input type="email" name="email" class="form-control"
                                value="<?= old('email', $settings['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                NPWP
                            </label>
                            <input name="tax_id" class="form-control"
                                value="<?= old('tax_id', $settings['tax_id'] ?? '') ?>">
                        </div>
                        <div class="col-12"><hr><h6><i class="bi bi-bank me-1"></i>Rekening Penerimaan Pembayaran Client</h6></div>
                        <div class="col-md-3"><label class="field-label">Nama Bank</label><input name="bank_name" class="form-control" value="<?= old('bank_name', $settings['bank_name'] ?? '') ?>" placeholder="BCA / Mandiri"></div>
                        <div class="col-md-3"><label class="field-label">Nama Pemilik Rekening</label><input name="bank_account_name" class="form-control" value="<?= old('bank_account_name', $settings['bank_account_name'] ?? '') ?>"></div>
                        <div class="col-md-3"><label class="field-label">Nomor Rekening</label><input name="bank_account_number" class="form-control" value="<?= old('bank_account_number', $settings['bank_account_number'] ?? '') ?>"></div>
                        <div class="col-md-3"><label class="field-label">Cabang</label><input name="bank_branch" class="form-control" value="<?= old('bank_branch', $settings['bank_branch'] ?? '') ?>"></div>
                    </div>
                </div>
            </div>
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-file-earmark-text me-2"></i>Default Quotation
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="field-label">
                                PPN (%)
                            </label>
                            <input type="number" step="0.01" min="0" name="default_tax_percent" class="form-control"
                                value="<?= old('default_tax_percent', $settings['default_tax_percent'] ?? 11) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="field-label">
                                Masa berlaku (hari)
                            </label>
                            <input type="number" min="0" name="default_validity_days" class="form-control"
                                value="<?= old('default_validity_days', $settings['default_validity_days'] ?? 10) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="field-label">
                                Nama penandatangan
                            </label>
                            <input name="signer_name" class="form-control"
                                value="<?= old('signer_name', $settings['signer_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Syarat pembayaran
                            </label>
                            <input name="default_payment_terms" class="form-control"
                                placeholder="Transfer 45 hari setelah invoice"
                                value="<?= old('default_payment_terms', $settings['default_payment_terms'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Syarat pengiriman
                            </label>
                            <input name="default_delivery_terms" class="form-control" placeholder="10 Hari"
                                value="<?= old('default_delivery_terms', $settings['default_delivery_terms'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Telepon penandatangan
                            </label>
                            <input name="signer_phone" class="form-control"
                                value="<?= old('signer_phone', $settings['signer_phone'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-images me-2"></i>Branding & Dokumen
                    </h3>
                </div>
                <div class="card-body">
                    <label class="field-label">
                        Logo perusahaan
                    </label>
                    <input type="file" name="logo" accept="image/png,image/jpeg,image/webp" class="form-control mb-2">
                    <?php if ($settingsLogoUrl): ?>
                    <img src="<?= esc($settingsLogoUrl) ?>" class="img-thumbnail mb-3" style="max-height:100px"
                        alt="Logo saat ini">
                    <?php endif; ?>
                    <label class="field-label">
                        Tanda tangan (image)
                    </label>
                    <input type="file" name="signature" accept="image/png,image/jpeg,image/webp"
                        class="form-control mb-2">
                    <?php if ($settingsSignatureUrl): ?>
                    <img src="<?= esc($settingsSignatureUrl) ?>" class="img-thumbnail mb-3"
                        style="max-height:100px" alt="Tanda tangan saat ini">
                    <?php endif; ?>
                    <label class="field-label">
                        Stempel (image)
                    </label>
                    <input type="file" name="stamp" accept="image/png,image/jpeg,image/webp" class="form-control mb-2">
                    <?php if ($settingsStampUrl): ?>
                    <img src="<?= esc($settingsStampUrl) ?>" class="img-thumbnail" style="max-height:100px"
                        alt="Stempel saat ini">
                    <?php endif; ?>
                    <div class="form-text mt-3">Format JPG, PNG, atau WEBP. Maksimal 5 MB per file.</div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-check2-circle me-1"></i>Simpan Setting
            </button>
        </div>
    </div>
</form>
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center"><h3 class="card-title mb-0"><i class="bi bi-bank me-2"></i>Rekening Perusahaan</h3><span class="text-body-secondary small">Pilih rekening saat quotation menjadi final</span></div>
    <div class="card-body">
        <form method="post" action="<?= site_url('settings/quotation/bank-accounts') ?>" class="row g-2 mb-4"><?= csrf_field() ?><div class="col-md-3"><input name="bank_name" class="form-control" placeholder="Nama bank" required></div><div class="col-md-3"><input name="account_name" class="form-control" placeholder="Nama pemilik rekening" required></div><div class="col-md-2"><input name="account_number" class="form-control" placeholder="Nomor rekening" required></div><div class="col-md-2"><input name="branch" class="form-control" placeholder="Cabang"></div><div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>Tambah</button></div><div class="col-md-3"><input name="currency" value="IDR" class="form-control" placeholder="Mata uang"></div><div class="col-md-5"><input name="notes" class="form-control" placeholder="Catatan rekening"></div><div class="col-md-2 form-check pt-2"><input type="checkbox" name="is_default" value="1" class="form-check-input" id="bank-default-new"><label class="form-check-label" for="bank-default-new">Default</label></div><div class="col-md-2 form-check pt-2"><input type="checkbox" name="is_active" value="1" checked class="form-check-input" id="bank-active-new"><label class="form-check-label" for="bank-active-new">Aktif</label></div></form>
        <div class="table-responsive"><table class="table table-sm align-middle"><thead><tr><th>Bank</th><th>Pemilik</th><th>Nomor</th><th>Cabang</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach (($bankAccounts ?? []) as $bank): ?><tr><td><strong><?= esc($bank['bank_name']) ?></strong><?php if ($bank['is_default']): ?><span class="badge text-bg-primary ms-1">Default</span><?php endif; ?><div class="small text-body-secondary"><?= esc($bank['currency'] ?? 'IDR') ?></div></td><td><?= esc($bank['account_name']) ?></td><td><?= esc($bank['account_number']) ?></td><td><?= esc($bank['branch'] ?? '-') ?></td><td><?= $bank['is_active'] ? '<span class="badge text-bg-success">Aktif</span>' : '<span class="badge text-bg-secondary">Nonaktif</span>' ?></td><td><div class="d-flex gap-1"><button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#bank-edit-<?= $bank['id'] ?>"><i class="bi bi-pencil"></i></button><form method="post" action="<?= site_url('settings/quotation/bank-accounts/' . public_id((int)$bank['id']) . '/delete') ?>" data-confirm data-confirm-title="Hapus rekening?"> <?= csrf_field() ?><button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></div></td></tr><tr class="collapse" id="bank-edit-<?= $bank['id'] ?>"><td colspan="6"><form method="post" action="<?= site_url('settings/quotation/bank-accounts/' . public_id((int)$bank['id'])) ?>" class="row g-2"><?= csrf_field() ?><div class="col-md-2"><input name="bank_name" class="form-control" value="<?= esc($bank['bank_name']) ?>" required></div><div class="col-md-2"><input name="account_name" class="form-control" value="<?= esc($bank['account_name']) ?>" required></div><div class="col-md-2"><input name="account_number" class="form-control" value="<?= esc($bank['account_number']) ?>" required></div><div class="col-md-2"><input name="branch" class="form-control" value="<?= esc($bank['branch'] ?? '') ?>"></div><div class="col-md-1"><input name="currency" class="form-control" value="<?= esc($bank['currency'] ?? 'IDR') ?>"></div><div class="col-md-1 form-check pt-2"><input type="checkbox" name="is_default" value="1" class="form-check-input" <?= $bank['is_default'] ? 'checked' : '' ?>> Default</div><div class="col-md-1 form-check pt-2"><input type="checkbox" name="is_active" value="1" class="form-check-input" <?= $bank['is_active'] ? 'checked' : '' ?>> Aktif</div><div class="col-md-1"><button class="btn btn-success w-100"><i class="bi bi-save"></i></button></div></form></td></tr><?php endforeach; ?></tbody></table></div>
    </div>
</div>
<?= $this->endSection() ?>
