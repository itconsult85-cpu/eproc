<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-key me-2"></i>Ganti Password</h3>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('password') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="field-label">
                            Password saat ini
                        </label>
                        <input type="password" name="current_password" class="form-control" required
                            autocomplete="current-password">
                    </div>
                    <div class="mb-3">
                        <label class="field-label">
                            Password baru
                        </label>
                        <input type="password" name="password" class="form-control" minlength="12" required
                            autocomplete="new-password">
                        <div class="form-text">
                            Minimal 12 karakter.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="field-label">
                            Konfirmasi password baru
                        </label>
                        <input type="password" name="password_confirmation" class="form-control" minlength="12" required
                            autocomplete="new-password">
                    </div>
                    <button class="btn btn-primary">
                        Simpan password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div><?= $this->endSection() ?>