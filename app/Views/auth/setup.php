<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Setup Administrator · EPROC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.9.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>

<body class="auth-page bg-body-secondary">
    <main class="container py-5">
        <div class="card shadow-sm mx-auto" style="max-width:620px">
            <div class="card-body p-4">
                <h1 class="h3">Setup administrator pertama</h1>
                <p class="text-body-secondary">Buat akun superadmin. Halaman ini otomatis tertutup setelah user pertama
                    dibuat.</p>
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
                <form method="post" action="<?= site_url('setup') ?>">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="field-label">
                                Nama lengkap
                            </label>
                            <input name="full_name" class="form-control" required value="<?= old('full_name') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Username
                            </label>
                            <input name="username" class="form-control" required value="<?= old('username') ?>">
                        </div>
                        <div class="col-12">
                            <label class="field-label">
                                Email
                            </label>
                            <input type="email" name="email" class="form-control" required value="<?= old('email') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">
                                Password
                            </label>
                            <input type="password" name="password" class="form-control" minlength="12" required>
                            <small class="text-body-secondary">Minimal
                                12 karakter.
                            </small>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-4">
                        Buat superadmin
                    </button>
                </form>
            </div>
        </div>
    </main>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>

</html>