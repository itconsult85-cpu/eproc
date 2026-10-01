<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$attentionCount = (int) ($draftQuotationCount ?? 0) + (int) ($unpaidBillCount ?? 0);
$formatCount = static fn(int|float $value): string => number_format($value, 0, ',', '.');
?>
<div class="app-content-header">
    <div class="page-intro dashboard-hero d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <div class="dashboard-kicker">EPROCUREMENT WORKSPACE</div>
            <h2 class="h4 mb-1"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
            <p class="text-body-secondary mb-0">Pantau alur penawaran, pengadaan vendor, dan dokumen serah terima dari
                satu tempat.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <?php if (can('quotations.create')): ?><a href="<?= esc(site_url('quotations/new')) ?>"
                class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg me-1"></i>Buat Penawaran</a><?php endif; ?>
            <?php if (can('client_po.create')): ?><a href="<?= esc(site_url('client-purchase-orders/new')) ?>"
                class="btn btn-outline-light text-nowrap"><i class="bi bi-file-earmark-plus me-1"></i>PO IN
                Baru</a><?php endif; ?>
        </div>
    </div>
</div>

<div class="dashboard-summary mb-4">
    <div class="dashboard-stat dashboard-stat-primary">
        <div class="dashboard-stat-icon"><i class="bi bi-file-earmark-text"></i></div>
        <div><span class="dashboard-stat-label">Total
                Penawaran</span><strong><?= $formatCount($quotationCount) ?></strong><small><?= $formatCount($approvedQuotationCount) ?>
                sudah disetujui</small></div>
    </div>
    <div class="dashboard-stat dashboard-stat-success">
        <div class="dashboard-stat-icon"><i class="bi bi-file-earmark-arrow-down"></i></div>
        <div><span class="dashboard-stat-label">PO IN
                Klien</span><strong><?= $formatCount($clientPurchaseOrderCount) ?></strong><small>Pesanan dari
                perusahaan klien</small></div>
    </div>
    <div class="dashboard-stat dashboard-stat-info">
        <div class="dashboard-stat-icon"><i class="bi bi-cart-check"></i></div>
        <div><span class="dashboard-stat-label">PO OUT
                Vendor</span><strong><?= $formatCount($purchaseOrderCount) ?></strong><small><?= $formatCount($vendorCount) ?>
                vendor terdaftar</small></div>
    </div>
    <div class="dashboard-stat dashboard-stat-warning">
        <div class="dashboard-stat-icon"><i class="bi bi-exclamation-circle"></i></div>
        <div><span class="dashboard-stat-label">Perlu
                Perhatian</span><strong><?= $formatCount($attentionCount) ?></strong><small><?= $formatCount($unpaidBillCount) ?>
                tagihan belum lunas</small></div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-7">
        <div class="dashboard-panel h-100">
            <div class="dashboard-panel-header">
                <div><span class="dashboard-kicker">WORKFLOW</span>
                    <h3 class="h6 mb-0">Status proses pengadaan</h3>
                </div><i class="bi bi-diagram-3 text-primary fs-4"></i>
            </div>
            <div class="workflow-grid">
                <a href="<?= esc(site_url('quotations')) ?>" class="workflow-item"><span
                        class="workflow-icon workflow-icon-muted"><i
                            class="bi bi-pencil-square"></i></span><span><strong>Draft Penawaran</strong><small>Perlu
                            dilengkapi</small></span><b><?= $formatCount($draftQuotationCount) ?></b></a>
                <a href="<?= esc(site_url('quotations')) ?>" class="workflow-item"><span
                        class="workflow-icon workflow-icon-warning"><i
                            class="bi bi-chat-square-text"></i></span><span><strong>Dalam
                            Negosiasi</strong><small>Menunggu tindak
                            lanjut</small></span><b><?= $formatCount($negotiationQuotationCount) ?></b></a>
                <a href="<?= esc(site_url('proforma-invoices')) ?>" class="workflow-item"><span
                        class="workflow-icon workflow-icon-success"><i
                            class="bi bi-check2-circle"></i></span><span><strong>Quotation Disetujui</strong><small>Siap
                            diproses</small></span><b><?= $formatCount($approvedQuotationCount) ?></b></a>
                <a href="<?= esc(site_url('vendor-bills')) ?>" class="workflow-item"><span
                        class="workflow-icon workflow-icon-danger"><i
                            class="bi bi-receipt"></i></span><span><strong>Tagihan Vendor</strong><small>Belum lunas /
                            sebagian</small></span><b><?= $formatCount($unpaidBillCount) ?></b></a>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="dashboard-panel h-100">
            <div class="dashboard-panel-header">
                <div><span class="dashboard-kicker">RINGKASAN MODUL</span>
                    <h3 class="h6 mb-0">Dokumen dan master data</h3>
                </div><i class="bi bi-grid-1x2 text-info fs-4"></i>
            </div>
            <div class="module-list">
                <a href="<?= esc(site_url('companies')) ?>"><span><i
                            class="bi bi-buildings me-2"></i>Perusahaan</span><strong><?= $formatCount($companyCount) ?></strong></a>
                <a href="<?= esc(site_url('products')) ?>"><span><i class="bi bi-box-seam me-2"></i>Katalog
                        Produk</span><strong><?= $formatCount($productCount) ?></strong></a>
                <a href="<?= esc(site_url('proforma-invoices')) ?>"><span><i class="bi bi-receipt me-2"></i>Proforma
                        Invoice</span><strong><?= $formatCount($proformaCount) ?></strong></a>
                <a href="<?= esc(site_url('client-delivery-notes')) ?>"><span><i class="bi bi-truck me-2"></i>Surat
                        Jalan Client</span><strong><?= $formatCount($deliveryNoteCount) ?></strong></a>
                <a href="<?= esc(site_url('basts')) ?>"><span><i
                            class="bi bi-clipboard-check me-2"></i>BAST</span><strong><?= $formatCount($bastCount) ?></strong></a>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-panel">
    <div class="dashboard-panel-header">
        <div>
            <span class="dashboard-kicker">
                AKTIVITAS
            </span>
            <h3 class="h6 mb-0">
                <i class="bi bi-clock-history me-2 text-primary"></i>
                Penawaran Terbaru
            </h3>
        </div>
        <a href="<?= esc(site_url('quotations')) ?>" class="btn btn-sm btn-outline-secondary text-nowrap">
            Lihat semua <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="datatable-container">
        <table id="recent-quotations-table" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Nomor Quotation</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?= $this->endSection() ?>