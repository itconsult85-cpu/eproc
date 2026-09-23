<?= $this->extend('layout') ?><?= $this->section('content') ?><h1 class="mb-3"><?= esc($title) ?></h1>
<form method="post" action="<?= esc($action) ?>" class="card"><?= csrf_field() ?>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="field-label">
                    Nama Perusahaan *
                </label>
                <input name="name" required maxlength="160" class="form-control"
                    value="<?= old('name', $company['name'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="field-label">
                    Telepon
                </label>
                <input name="phone" class="form-control" value="<?= old('phone', $company['phone'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="field-label">Prefix quotation</label>
                <input name="quotation_prefix" maxlength="12" class="form-control" placeholder="CCIP"
                    value="<?= old('quotation_prefix', $company['quotation_prefix'] ?? 'CCIP') ?>">
            </div>
            <div class="col-md-4">
                <label class="field-label">Kode quotation</label>
                <input name="quotation_code" maxlength="30" class="form-control" placeholder="TRE-ICA"
                    value="<?= old('quotation_code', $company['quotation_code'] ?? '') ?>">
            </div>
            <div class="col-12">
                <div class="form-text">Nomor dibuat otomatis: prefix + nomor urut 3 digit + tanggal + kode + bulan Romawi + tahun. Kosongkan kode untuk memakai kode otomatis dari nama perusahaan.</div>
            </div>
            <div class="col-12">
                <label class="field-label">
                    Alamat
                </label>
                <textarea name="address"
                    class="form-control"><?= old('address', $company['address'] ?? '') ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="field-label">
                    Email
                </label>
                <input type="email" name="email" class="form-control"
                    value="<?= old('email', $company['email'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="field-label">
                    Nama PIC
                </label>
                <input name="pic_name" class="form-control" value="<?= old('pic_name', $company['pic_name'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="field-label">
                    Telepon PIC
                </label>
                <input name="pic_phone" class="form-control"
                    value="<?= old('pic_phone', $company['pic_phone'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="field-label">
                    Catatan
                </label>
                <textarea name="notes" class="form-control"><?= old('notes', $company['notes'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="/companies" class="btn btn-light">
            Batal
        </a>
        <button class="btn btn-primary">
            Simpan
        </button>
    </div>
</form><?= $this->endSection() ?>
