<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3"><div><h2 class="h5 mb-1"><i class="bi bi-truck me-2"></i><?= esc($title) ?></h2><p class="text-body-secondary mb-0">Kelola data vendor, kontak, rekening bank, dan status kerja sama.</p></div><a href="/vendors" class="btn btn-light text-nowrap"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<form method="post" action="<?= esc($action) ?>" class="card form-card"><div class="card-body row g-3"><?= csrf_field() ?>
<div class="col-md-6"><label class="form-label">Nama Vendor *</label><input name="name" required class="form-control" value="<?= esc($vendor['name']??'') ?>"></div>
<div class="col-md-3"><label class="form-label">Kode Vendor</label><input name="code" class="form-control" value="<?= esc($vendor['code']??'') ?>"></div>
<div class="col-md-3"><label class="form-label">PIC</label><input name="pic_name" class="form-control" value="<?= esc($vendor['pic_name']??'') ?>"></div>
<div class="col-md-6"><label class="form-label">Telepon</label><input name="phone" class="form-control" value="<?= esc($vendor['phone']??'') ?>"></div>
<div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= esc($vendor['email']??'') ?>"></div>
<div class="col-12"><label class="form-label">Alamat</label><textarea name="address" class="form-control"><?= esc($vendor['address']??'') ?></textarea></div>
<div class="col-12"><hr class="my-1"><h6><i class="bi bi-bank me-1"></i>Rekening Bank Vendor</h6></div>
<div class="col-md-3"><label class="form-label">Nama Bank</label><input name="bank_name" class="form-control" value="<?= esc($vendor['bank_name']??'') ?>" placeholder="BCA / Mandiri"></div>
<div class="col-md-3"><label class="form-label">Nama Pemilik Rekening</label><input name="bank_account_name" class="form-control" value="<?= esc($vendor['bank_account_name']??'') ?>"></div>
<div class="col-md-3"><label class="form-label">Nomor Rekening</label><input name="bank_account_number" class="form-control" value="<?= esc($vendor['bank_account_number']??'') ?>"></div>
<div class="col-md-3"><label class="form-label">Cabang</label><input name="bank_branch" class="form-control" value="<?= esc($vendor['bank_branch']??'') ?>"></div>
<div class="col-md-6"><label class="form-label">NPWP Vendor</label><input name="tax_id" class="form-control" value="<?= esc($vendor['tax_id']??'') ?>"></div>
<div class="col-md-6"><label class="form-label">Termin Pembayaran</label><input name="payment_terms" class="form-control" value="<?= esc($vendor['payment_terms']??'') ?>"></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1" <?= ($vendor['is_active']??1)?'selected':'' ?>>Aktif</option><option value="0" <?= !($vendor['is_active']??1)?'selected':'' ?>>Nonaktif</option></select></div>
<div class="col-12"><label class="form-label">Catatan</label><textarea name="notes" class="form-control"><?= esc($vendor['notes']??'') ?></textarea></div>
</div><div class="card-footer d-flex justify-content-end gap-2"><a href="/vendors" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan Vendor</button></div></form>
<?= $this->endSection() ?>
