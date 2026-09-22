<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Eprocurement') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.9.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.7/css/responsive.bootstrap5.min.css">
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

    .page-intro {
        background: linear-gradient(135deg, rgba(13, 110, 253, .09), rgba(13, 202, 240, .04));
        border: 1px solid rgba(13, 110, 253, .1);
        border-radius: .8rem;
        padding: 1.15rem 1.25rem;
    }

    .card {
        border: 0;
        box-shadow: 0 .125rem .5rem rgba(25, 42, 70, .07);
    }

    .card-header {
        border-bottom-color: rgba(0, 0, 0, .06);
    }

    .form-card .card-body {
        padding: 1.5rem;
    }

    .field-label {
        display: inline-block;
        margin-bottom: .42rem;
        color: #f1f5f9;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .045em;
        line-height: 1.3;
        text-transform: uppercase;
    }

    .field-label.required::after {
        content: ' *';
        color: var(--bs-danger);
    }

    .form-control,
    .form-select {
        color: #f8fafc;
        background-color: rgba(15, 23, 42, .28);
    }

    .form-control::placeholder {
        color: #a8b4c5;
        opacity: 1;
    }

    .form-control:focus,
    .form-select:focus {
        color: #ffffff;
        background-color: rgba(15, 23, 42, .42);
    }

    .form-control,
    .form-select {
        border-color: #d0d5dd;
        min-height: 2.55rem;
        transition: border-color .16s ease, box-shadow .16s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .12);
    }

    textarea.form-control {
        min-height: 6rem;
    }

    .btn {
        transition: transform .16s ease, box-shadow .16s ease;
    }

    .btn:active {
        transform: scale(.97);
    }

    .btn-group form {
        display: inline-flex;
    }

    .btn-group {
        white-space: nowrap;
    }

    .btn-group .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .table thead th {
        white-space: nowrap;
        color: #475467;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .035em;
    }

    .table td {
        vertical-align: middle;
    }

    .dataTables_wrapper .dt-layout-row {
        align-items: center;
        gap: .75rem;
    }

    .dataTables_wrapper .dt-search input,
    .dataTables_wrapper .dt-length select {
        border: 1px solid #d0d5dd;
        border-radius: .45rem;
        padding: .4rem .65rem;
    }

    .dataTables_wrapper .dt-info {
        color: #667085;
        font-size: .875rem;
    }

    .dataTables_wrapper .pagination {
        margin-bottom: 0;
    }

    .datatable-container {
        width: 100%;
        overflow: visible;
    }

    .datatable-container table {
        width: 100% !important;
    }

    .dtr-control {
        cursor: pointer;
    }

    .dt-empty {
        padding: 2.5rem 1rem !important;
        color: #667085;
    }

    @media (max-width: 575.98px) {
        .dataTables_wrapper .dt-layout-row {
            flex-direction: column;
            align-items: stretch;
        }

        .dataTables_wrapper .dt-layout-cell {
            text-align: left !important;
        }
    }
    </style>
</head>

<body class="layout-fixed fixed-header fixed-footer sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"
                            aria-label="Toggle sidebar"><i class="bi bi-list"></i></a></li>
                    <li class="nav-item d-none d-md-block"><a href="/" class="nav-link fw-semibold">Eprocurement
                            Workspace</a></li>
                </ul>
                <div class="ms-auto d-flex align-items-center gap-3"><span class="text-secondary small d-none d-md-inline"><?= esc(auth_user('full_name')) ?> · <?= esc(auth_user('role')) ?></span><a href="<?= site_url('password') ?>" class="btn btn-sm btn-light" title="Ganti password"><i class="bi bi-key"></i></a><form method="post" action="<?= site_url('logout') ?>" class="d-inline"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger" type="submit">Logout</button></form></div>
            </div>
        </nav>
        <?php
        $sidebarLogo = model(\App\Models\QuotationSettingModel::class)->current()['logo_path'] ?? null;
        $sidebarLogoUrl = $sidebarLogo
            ? (preg_match('#^https?://#i', $sidebarLogo) ? $sidebarLogo : base_url(ltrim($sidebarLogo, '/')))
            : null;
        ?>
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand"><a href="<?= esc(site_url('/')) ?>" class="brand-link text-decoration-none">
                    <?php if ($sidebarLogoUrl): ?>
                    <img src="<?= esc($sidebarLogoUrl) ?>" class="brand-image rounded opacity-75" alt="Logo EPROC">
                    <?php else: ?>
                    <i class="bi bi-file-earmark-richtext brand-image opacity-75"></i>
                    <?php endif; ?>
                    <span class="brand-text fw-light">EPROC</span>
                </a></div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                        <li class="nav-header">WORKSPACE</li>
                        <?php if (can('dashboard.view')): ?><li class="nav-item"><a href="/" class="nav-link"><i class="nav-icon bi bi-speedometer2"></i>
                                <p>Dashboard</p>
                            </a></li><?php endif; ?>
                        <?php if (can('companies.view')): ?><li class="nav-item"><a href="/companies" class="nav-link"><i
                                    class="nav-icon bi bi-buildings"></i>
                                <p>Perusahaan</p>
                            </a></li><?php endif; ?>
                        <?php if (can('products.view')): ?><li class="nav-item"><a href="/products" class="nav-link"><i
                                    class="nav-icon bi bi-box-seam"></i>
                                <p>Katalog Produk</p>
                            </a></li><?php endif; ?>
                        <?php if (can('quotations.view')): ?><li class="nav-item"><a href="/quotations" class="nav-link"><i
                                    class="nav-icon bi bi-file-earmark-text"></i>
                                <p>Penawaran</p>
                            </a></li><?php endif; ?>
                        <li class="nav-header">KONFIGURASI</li>
                        <?php if (can('settings.quotation')): ?><li class="nav-item"><a href="/settings/quotation" class="nav-link"><i
                                    class="nav-icon bi bi-sliders"></i>
                                <p>Setting Quotation</p>
                            </a></li><?php endif; ?>
                        <?php if (can('users.manage')): ?><li class="nav-item"><a href="/users" class="nav-link"><i class="nav-icon bi bi-people"></i><p>Manajemen Pengguna</p></a></li><?php endif; ?>
                    </ul>
                </nav>
            </div>
        </aside>
        <main class="app-main">

            <div class="app-content">
                <div class="container-fluid">
                    <?php if ($message = session()->getFlashdata('message')): ?><div
                        class="alert alert-success alert-dismissible fade show"><i
                            class="bi bi-check-circle me-1"></i><?= esc($message) ?><button type="button"
                            class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
                    <?php if ($errors = session()->getFlashdata('errors')): ?><div
                        class="alert alert-danger alert-dismissible fade show"><i
                            class="bi bi-exclamation-triangle me-1"></i>
                        <ul class="mb-0 d-inline-block align-middle"><?php foreach ($errors as $error): ?><li>
                                <?= esc($error) ?></li><?php endforeach; ?></ul><button type="button" class="btn-close"
                            data-bs-dismiss="alert"></button>
                    </div><?php endif; ?>
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>
        <footer class="app-footer"><strong>Eprocurement</strong> &mdash; internal workspace</footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.9.1/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.7/js/responsive.bootstrap5.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>
