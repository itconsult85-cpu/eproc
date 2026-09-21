<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Eprocurement') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .app-sidebar .nav-link p {
            margin-bottom: 0;
        }

        .brand-image {
            width: 28px;
            height: 28px;
            object-fit: contain;
        }

        .content-header h1 {
            font-size: 1.55rem;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body border-bottom">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button class="nav-link" data-lte-toggle="sidebar" type="button" aria-label="Toggle sidebar">
                            <i class="bi bi-list fs-4"></i>
                        </button>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="/" class="nav-link fw-semibold">Eprocurement Workspace</a></li>
                </ul>
                <div class="ms-auto text-secondary small">CodeIgniter 4.7.4</div>
            </div>
        </nav>
        <aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="/" class="brand-link text-decoration-none">
                    <i class="bi bi-file-earmark-richtext brand-image opacity-75"></i>
                    <span class="brand-text fw-light">EPROC</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                        <li class="nav-header">WORKSPACE</li>
                        <li class="nav-item"><a href="/" class="nav-link"><i class="nav-icon bi bi-speedometer2"></i>
                                <p>Dashboard</p>
                            </a></li>
                        <li class="nav-item"><a href="/companies" class="nav-link"><i class="nav-icon bi bi-buildings"></i>
                                <p>Perusahaan</p>
                            </a></li>
                        <li class="nav-item"><a href="/products" class="nav-link"><i class="nav-icon bi bi-box-seam"></i>
                                <p>Katalog Produk</p>
                            </a></li>
                        <li class="nav-item"><a href="/quotations" class="nav-link"><i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>Penawaran</p>
                            </a></li>
                        <li class="nav-header">KONFIGURASI</li>
                        <li class="nav-item"><a href="/settings/quotation" class="nav-link"><i class="nav-icon bi bi-sliders"></i>
                                <p>Setting Quotation</p>
                            </a></li>
                    </ul>
                </nav>
            </div>
        </aside>
        <main class="app-main">
            <div class="app-content-header content-header py-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="mb-0"><?= esc($title ?? 'Eprocurement') ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <?php if ($message = session()->getFlashdata('message')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i><?= esc($message) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
                    <?php if ($errors = session()->getFlashdata('errors')): ?><div class="alert alert-danger">
                            <ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul>
                        </div><?php endif; ?>
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>
        <footer class="app-footer"><strong>Eprocurement</strong> &mdash; internal workspace</footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>
</body>

</html>