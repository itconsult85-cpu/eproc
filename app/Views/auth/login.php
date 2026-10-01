<!doctype html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        html,
        body {
            background: #0b1326;
            color: #e5e7eb;
        }

        .auth-page {
            min-height: 100dvh;
            height: 100dvh;
            overflow: hidden;
        }
    </style>
    <title><?= esc($title) ?> · EPROC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.9.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>

<body class="auth-page d-flex align-items-center py-4" data-bs-theme="dark">
    <main class="container auth-container">
        <div class="login-card card shadow-lg">
            <div class="login-card-body">
                <header class="login-header text-center">
                    <?php
                    $loginLogo = $settings['logo_path'] ?? null;
                    $loginLogoUrl = $loginLogo
                        ? (preg_match('#^https?://#i', $loginLogo) ? $loginLogo : base_url(ltrim($loginLogo, '/')))
                        : null;
                    ?>
                    <?php if ($loginLogoUrl): ?>
                        <div class="login-logo-wrap mb-3">
                            <img src="<?= esc($loginLogoUrl) ?>" class="login-logo" alt="Logo perusahaan">
                        </div>
                    <?php else: ?>
                        <div class="brand-mark mx-auto mb-3" aria-hidden="true"><i class="bi bi-shield-lock"></i></div>
                    <?php endif; ?>
                    <div class="login-eyebrow">Eprocurement Workspace</div>
                    <h1 class="login-title mb-2"><?= esc($settings['company_name'] ?? 'EPROC') ?></h1>
                    <p class="login-subtitle mb-0">Masuk untuk mengelola proses pengadaan perusahaan.</p>
                </header>

                <?php if ($message = session()->getFlashdata('message')): ?>
                    <div class="alert alert-success login-alert" role="status">
                        <i class="bi bi-check-circle me-2" aria-hidden="true"></i><?= esc($message) ?>
                    </div>
                <?php endif; ?>
                <?php if ($error = session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger login-alert" role="alert">
                        <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i><?= esc($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('login') ?>" class="login-form">
                    <?= csrf_field() ?>
                    <div class="login-field">
                        <label class="field-label" for="login">Username atau email</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" aria-hidden="true"><i class="bi bi-person"></i></span>
                            <input id="login" class="form-control" name="login" autocomplete="username" required
                                autofocus value="<?= esc(old('login')) ?>" placeholder="Masukkan username atau email">
                        </div>
                    </div>
                    <div class="login-field">
                        <label class="field-label" for="password">Password</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" aria-hidden="true"><i class="bi bi-lock"></i></span>
                            <input id="password" type="password" class="form-control" name="password"
                                autocomplete="current-password" required placeholder="Masukkan password">
                            <button class="btn btn-outline-secondary login-password-toggle" type="button"
                                data-action="toggle-password" aria-label="Tampilkan password"
                                title="Tampilkan password">
                                <i class="bi bi-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-lg w-100 login-submit" type="submit">
                        <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Masuk ke Workspace
                    </button>
                </form>
            </div>
            <footer class="login-card-footer text-center">
                <span><i class="bi bi-shield-check me-1" aria-hidden="true"></i>Akses aman untuk pengguna
                    terdaftar</span>
            </footer>
        </div>
    </main>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>

</html>