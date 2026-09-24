<?= $this->extend('layout') ?><?= $this->section('content') ?><div
    class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">
            Manajemen Pengguna
        </h1>
        <p class="text-body-secondary mb-0">
            Kelola akun dan hak akses aplikasi.
        </p>
    </div><a href="<?= site_url('users/new') ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>Tambah pengguna
    </a>
</div>
<div class="card card-outline card-primary">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Login terakhir</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><strong><?= esc($u['full_name']) ?></strong>
                            <div class="small text-body-secondary">@<?= esc($u['username']) ?> · <?= esc($u['email']) ?>
                            </div>
                        </td>
                        <td>
                            <span
                                class="badge text-bg-<?= $u['role'] === 'admin' ? 'warning' : ($u['role'] === 'superadmin' ? 'danger' : 'secondary') ?>"><?= esc($u['role']) ?>
                            </span>
                        </td>
                        <td><?= $u['is_active'] ? '<span class="text-success">Aktif</span>' : '<span class="text-danger">Nonaktif</span>' ?>
                        </td>
                        <td><?= esc($u['last_login_at'] ?: 'Belum pernah') ?></td>
                        <td class="text-end"><?php if ($u['role'] !== 'superadmin'): ?>
                            <a class="btn btn-sm btn-outline-primary"
                                href="<?= site_url('users/' . $u['id'] . '/edit') ?>">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form class="d-inline" method="post"
                                action="<?= site_url('users/' . $u['id'] . '/delete') ?>"
                                data-confirm data-confirm-title="Hapus pengguna?"
                                data-confirm-message="Pengguna ini akan dihapus dan tidak dapat dipulihkan."
                                data-confirm-label="Ya, hapus" data-confirm-variant="danger">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="text-body-secondary small">
                                Dilindungi
                            </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div><?= $this->endSection() ?>
