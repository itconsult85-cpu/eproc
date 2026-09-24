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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>

<body class="layout-fixed fixed-header fixed-footer sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"
                            aria-label="Toggle sidebar">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="/" class="nav-link fw-semibold">
                            Eprocurement Workspace
                        </a>
                    </li>
                </ul>
                <?php \App\Libraries\NotificationService::sync(); $notificationModel = model(\App\Models\NotificationModel::class); $notifications = $notificationModel->forUser((int) auth_user('id')); $unreadNotifications = $notificationModel->unreadCount((int) auth_user('id')); ?>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <?php if (can('notifications.view')): ?><div class="dropdown">
                        <button class="btn btn-sm btn-light position-relative" data-bs-toggle="dropdown" aria-label="Notifikasi"><i class="bi bi-bell"></i><?php if ($unreadNotifications): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $unreadNotifications > 99 ? '99+' : $unreadNotifications ?></span><?php endif; ?></button>
                        <div class="dropdown-menu dropdown-menu-end notification-menu shadow-sm"><div class="d-flex justify-content-between px-3 py-2"><strong>Notifikasi</strong><form method="post" action="<?= site_url('notifications/read-all') ?>"><?= csrf_field() ?><button class="btn btn-link btn-sm p-0">Tandai dibaca</button></form></div><div class="dropdown-divider"></div><?php if (!$notifications): ?><span class="dropdown-item-text text-body-secondary">Tidak ada notifikasi.</span><?php else: foreach ($notifications as $notification): ?><a class="dropdown-item notification-item <?= $notification['is_read'] ? '' : 'unread' ?>" href="<?= esc(site_url('notifications/'.$notification['id'].'/read?return='.urlencode(current_url()))) ?>"><strong><?= esc($notification['title']) ?></strong><small class="d-block text-body-secondary"><?= esc($notification['message']) ?></small></a><?php endforeach; endif; ?></div>
                    </div><?php endif; ?>
                    <span class="text-secondary small d-none d-md-inline"><?= esc(auth_user('full_name')) ?> ·
                        <?= esc(auth_user('role')) ?>
                    </span>
                    <a href="<?= site_url('password') ?>" class="btn btn-sm btn-light" title="Ganti password">
                        <i class="bi bi-key"></i>
                    </a>
                    <form method="post" action="<?= site_url('logout') ?>" class="d-inline"><?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-danger" type="submit">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        <?php
        $sidebarLogo = model(\App\Models\QuotationSettingModel::class)->current()['logo_path'] ?? null;
        $sidebarLogoUrl = $sidebarLogo
            ? (preg_match('#^https?://#i', $sidebarLogo) ? $sidebarLogo : base_url(ltrim($sidebarLogo, '/')))
            : null;
        $currentPath = trim((string) service('uri')->getPath(), '/');
        $isSidebarActive = static function (string $path) use ($currentPath): bool {
            return $path === ''
                ? $currentPath === ''
                : ($currentPath === $path || str_starts_with($currentPath, $path . '/'));
        };
        ?>
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand"><a href="<?= esc(site_url('/')) ?>" class="brand-link text-decoration-none">
                    <?php if ($sidebarLogoUrl): ?>
                    <img src="<?= esc($sidebarLogoUrl) ?>" class="brand-image rounded opacity-75" alt="Logo EPROC">
                    <?php else: ?>
                    <i class="bi bi-file-earmark-richtext brand-image opacity-75"></i>
                    <?php endif; ?>
                    <span class="brand-text fw-light">
                        EPROC
                    </span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                        <li class="nav-header">
                            WORKSPACE
                        </li>
                        <?php if (can('dashboard.view')): ?>
                        <li class="nav-item">
                            <a href="/" class="nav-link <?= $isSidebarActive('') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (can('companies.view')): ?>
                        <li class="nav-item">
                            <a href="/companies" class="nav-link <?= $isSidebarActive('companies') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-buildings"></i>
                                <p>
                                    Perusahaan
                                </p>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (can('products.view')): ?>
                        <li class="nav-item">
                            <a href="/products" class="nav-link <?= $isSidebarActive('products') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-box-seam"></i>
                                <p>
                                    Katalog Produk
                                </p>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (can('quotations.view')): ?>
                        <li class="nav-item">
                            <a href="/quotations" class="nav-link <?= $isSidebarActive('quotations') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>
                                    Penawaran
                                </p>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (can('proforma.view')): ?><li class="nav-item"><a href="/proforma-invoices" class="nav-link <?= $isSidebarActive('proforma-invoices') ? 'active' : '' ?>"><i class="nav-icon bi bi-receipt"></i><p>Proforma Invoice</p></a></li><?php endif; ?>
                        <?php if (can('vendors.view')): ?><li class="nav-item"><a href="/vendors" class="nav-link <?= $isSidebarActive('vendors') ? 'active' : '' ?>"><i class="nav-icon bi bi-truck"></i><p>Vendor</p></a></li><?php endif; ?>
                        <?php if (can('purchase_orders.view')): ?><li class="nav-item"><a href="/purchase-orders" class="nav-link <?= $isSidebarActive('purchase-orders') ? 'active' : '' ?>"><i class="nav-icon bi bi-cart-check"></i><p>Purchase Order</p></a></li><?php endif; ?>
                        <?php if (can('vendor_bills.view')): ?><li class="nav-item"><a href="/vendor-bills" class="nav-link <?= $isSidebarActive('vendor-bills') ? 'active' : '' ?>"><i class="nav-icon bi bi-journal-text"></i><p>Tagihan Vendor</p></a></li><?php endif; ?>
                        <li class="nav-header">
                            KONFIGURASI
                        </li>
                        <?php if (can('settings.quotation')): ?>
                        <li class="nav-item">
                            <a href="/settings/quotation" class="nav-link <?= $isSidebarActive('settings/quotation') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-sliders"></i>
                                <p>
                                    Setting Quotation
                                </p>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (can('users.manage')): ?>
                        <li class="nav-item">
                            <a href="/users" class="nav-link <?= $isSidebarActive('users') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-people"></i>
                                <p>
                                    Manajemen Pengguna
                                </p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </aside>
        <main class="app-main">

            <div class="app-content">
                <div class="container-fluid">
                    <?php if ($message = session()->getFlashdata('message')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-1"></i><?= esc($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert">
                        </button>
                    </div>
                    <?php endif; ?>
                    <?php if ($error = session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-1"></i><?= esc($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                    <?php endif; ?>
                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <ul class="mb-0 d-inline-block align-middle">
                            <?php foreach ($errors as $error): ?>
                            <li>
                                <?= esc($error) ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"> </button>
                    </div><?php endif; ?>
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>
        <footer class="app-footer">
            <strong>Eprocurement</strong> &mdash; internal workspace
        </footer>
    </div>
    <div class="modal fade" id="appConfirmModal" tabindex="-1" aria-labelledby="appConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="confirm-modal-icon" data-confirm-icon aria-hidden="true"><i class="bi bi-question-lg"></i></div>
                        <div>
                            <div class="small text-uppercase text-body-secondary fw-semibold letter-spacing-1">Konfirmasi tindakan</div>
                            <h5 class="modal-title mb-0" id="appConfirmModalLabel" data-confirm-title>Konfirmasi</h5>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body pt-3">
                    <p class="mb-0 text-body-secondary" data-confirm-message>Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" data-confirm-submit>Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>
