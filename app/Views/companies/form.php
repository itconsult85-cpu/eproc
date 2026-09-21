<?= $this->extend('layout') ?><?= $this->section('content') ?><h1 class="mb-3"><?= esc($title) ?></h1>
<form method="post" action="<?= esc($action) ?>" class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nama Perusahaan *</label>
                <input name="name" required maxlength="160" class="form-control"
                    value="<?= old('name', $company['name'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Telepon</label>
                <input name="phone" class="form-control" value="<?= old('phone', $company['phone'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <textarea name="address"
                    class="form-control"><?= old('address', $company['address'] ?? '') ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="<?= old('email', $company['email'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama PIC</label>
                <input name="pic_name" class="form-control" value="<?= old('pic_name', $company['pic_name'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Telepon PIC</label>
                <input name="pic_phone" class="form-control"
                    value="<?= old('pic_phone', $company['pic_phone'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control"><?= old('notes', $company['notes'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="/companies" class="btn btn-light">Batal</a> <?= $this->extend('layout') ?>
        <?= $this->section('content') ?>
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <form method="post" action="<?= esc($action) ?>" class="card form-card">
                    <div class="card-header py-3">
                        <h2 class="h5 card-title mb-0"><i
                                class="bi bi-building text-primary me-2"></i><?= esc($title) ?></h2>
                        <p class="text-body-secondary small mb-0 mt-1">Lengkapi informasi perusahaan agar mudah dipakai
                            saat
                            membuat penawaran.</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-8"><label class="form-label required" for="company-name">Nama
                                    Perusahaan</label><input id="company-name" name="name" required maxlength="160"
                                    class="form-control" placeholder="Contoh: PT Maju Bersama"
                                    value="<?= old('name', $company['name'] ?? '') ?>"></div>
                            <div class="col-md-4"><label class="form-label" for="company-phone">Telepon</label><input
                                    id="company-phone" name="phone" type="tel" class="form-control"
                                    placeholder="021-xxxxxxx" value="<?= old('phone', $company['phone'] ?? '') ?>">
                            </div>
                            <div class="col-12"><label class="form-label" for="company-address">Alamat</label><textarea
                                    id="company-address" name="address" class="form-control" rows="3"
                                    placeholder="Alamat kantor atau lokasi customer"><?= old('address', $company['address'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-6"><label class="form-label" for="company-email">Email</label><input
                                    id="company-email" type="email" name="email" class="form-control"
                                    placeholder="nama@perusahaan.com"
                                    value="<?= old('email', $company['email'] ?? '') ?>">
                            </div>
                            <div class="col-md-6"><label class="form-label" for="company-pic">Nama PIC</label><input
                                    id="company-pic" name="pic_name" class="form-control"
                                    placeholder="Nama contact person"
                                    value="<?= old('pic_name', $company['pic_name'] ?? '') ?>"></div>
                            <div class="col-md-6"><label class="form-label" for="company-pic-phone">Telepon
                                    PIC</label><input id="company-pic-phone" name="pic_phone" type="tel"
                                    class="form-control" placeholder="Nomor PIC"
                                    value="<?= old('pic_phone', $company['pic_phone'] ?? '') ?>"></div>
                            <div class="col-12"><label class="form-label" for="company-notes">Catatan</label><textarea
                                    id="company-notes" name="notes" class="form-control" rows="3"
                                    placeholder="Catatan internal (opsional)"><?= old('notes', $company['notes'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-2"><a href="/companies"
                            class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Batal</a><button type="submit"
                            class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Simpan Perusahaan</button>
                    </div>
                </form>
            </div>
        </div>
        <?= $this->endSection() ?><button class="btn btn-primary">Simpan</button>
    </div>
</form><?= $this->endSection() ?>