<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1">
                <i class="bi bi-person-gear me-2"></i>
                <?= esc($title) ?>
            </h2>
            <p class="text-body-secondary mb-0">
                Permission ditetapkan langsung per akun.
            </p>
        </div>
        <a href="<?= site_url('users') ?>" class="btn btn-light text-nowrap">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>
<?php if ($errors = session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
                <li>
                    <?= esc($e) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-page" method="post" enctype="multipart/form-data"
    action="<?= $user ? site_url('users/' . public_id($user['id'])) : site_url('users') ?>">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="field-label">
                                Nama lengkap
                            </label>
                            <input name="full_name" class="form-control" required
                                value="<?= old('full_name', $user['full_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Username
                            </label>
                            <input name="username" class="form-control" required
                                value="<?= old('username', $user['username'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Email
                            </label>
                            <input type="email" name="email" class="form-control" required
                                value="<?= old('email', $user['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Role
                            </label>
                            <select name="role" class="form-select">
                                <option value="user"
                                    <?= old('role', $user['role'] ?? '') === 'user' ? 'selected' : '' ?>>
                                    User
                                </option>
                                <option value="admin"
                                    <?= old('role', $user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>
                                    Admin
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Password
                                <?= $user ? '(kosongkan jika tidak diubah)' : '' ?>
                            </label>
                            <input type="password" name="password" class="form-control" minlength="12"
                                <?= $user ? '' : 'required' ?>>
                            <small class="text-body-secondary">
                                Minimal 12 karakter.
                            </small>
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Avatar
                            </label>
                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Tanda tangan Prepared by
                            </label>
                            <input type="file" name="signature" accept="image/jpeg,image/png,image/webp"
                                class="form-control">
                            <small class="text-body-secondary">Format JPG, PNG, atau WEBP, maksimal 5 MB.</small>
                            <?php if (! empty($user['signature_path'])): ?>
                                <div class="mt-2"><img src="<?= esc(base_url(ltrim($user['signature_path'], '/'))) ?>" class="img-thumbnail" style="max-height:80px" alt="Tanda tangan user saat ini"></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active"
                                <?= old('is_active', $user['is_active'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="active">
                                Akun aktif
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card card-info card-outline mb-3">
                <div class="card-header">
                    <h3 class="card-title">Akses Perusahaan</h3>
                </div>
                <div class="card-body">
                    <p class="small text-body-secondary">User hanya dapat melihat dan mengelola data yang berhubungan dengan perusahaan yang dipilih.</p>
                    <?php $assignedCompanies = $assignedCompanies ?? []; ?>
                    <?php if ($companies): ?>
                        <div class="vstack gap-2">
                            <?php foreach ($companies as $company): ?>
                                <label class="form-check border rounded p-2">
                                    <input class="form-check-input" type="checkbox" name="company_ids[]"
                                        value="<?= (int) $company['id'] ?>"
                                        <?= in_array((int) $company['id'], array_map('intval', (array) old('company_ids', $assignedCompanies)), true) ? 'checked' : '' ?>>
                                    <span class="form-check-label"><?= esc($company['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">Belum ada perusahaan yang dapat ditetapkan.</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Permission
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-check form-check-primary border rounded p-2 mb-3">
                        <input class="form-check-input" type="checkbox" id="permission-select-all">
                        <label class="form-check-label fw-semibold" for="permission-select-all">
                            Pilih Semua Permission
                        </label>
                    </div>
                    <?php $group = '';
                    foreach ($permissions as $p): ?>
                        <?php if ($group !== $p['group_name']): ?>
                            <?php if ($group !== ''): ?>
                </div>
            <?php endif; ?>
            <?php $group = $p['group_name']; ?>
            <h6 class="text-uppercase text-body-secondary mt-2">
                <?= esc($group) ?>
            </h6>
            <div class="vstack gap-2">
            <?php endif; ?>
            <label class="form-check">
                <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]"
                    value="<?= esc($p['permission_key']) ?>"
                    <?= in_array($p['permission_key'], $assigned, true) ? 'checked' : '' ?>>
                <span class="form-check-label">
                    <?= esc($p['label']) ?>
                </span>
            </label>
        <?php endforeach; ?>
            </div>
            </div>
            <button class="btn btn-primary w-100">
                Simpan pengguna
            </button>
        </div>
    </div>
</form><?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('permission-select-all');
    const permissions = Array.from(document.querySelectorAll('.permission-checkbox'));

    if (!selectAll || permissions.length === 0) {
        return;
    }

    function syncSelectAll() {
        const checkedCount = permissions.filter((permission) => permission.checked).length;
        selectAll.checked = checkedCount === permissions.length;
        selectAll.indeterminate = checkedCount > 0 && checkedCount < permissions.length;
    }

    selectAll.addEventListener('change', function () {
        permissions.forEach((permission) => {
            permission.checked = selectAll.checked;
        });
        selectAll.indeterminate = false;
    });

    permissions.forEach((permission) => {
        permission.addEventListener('change', syncSelectAll);
    });

    syncSelectAll();
});
</script>
<?= $this->endSection() ?>
