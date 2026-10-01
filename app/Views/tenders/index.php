<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1">
                <i class="bi bi-folder2-open me-2"></i>
                Dokumen Tender
            </h2>
            <p class="text-body-secondary mb-0">
                Kelola dokumen tender, jadwal, masa berlaku, dan file pendukung.
            </p>
        </div>
        <a href="/tenders/new" class="btn btn-primary"><i class="bi bi-cloud-arrow-up me-1"></i>
            Upload Dokumen Tender
        </a>
    </div>
</div>
<?php if (($expiredCount ?? 0) > 0 || ($expiringCount ?? 0) > 0): ?><div
        class="alert <?= ($expiredCount ?? 0) > 0 ? 'alert-danger' : 'alert-warning' ?> d-flex justify-content-between align-items-center">
        <div>
            <strong><i class="bi bi-exclamation-triangle me-1"></i>Perhatian masa aktif dokumen</strong>
            <div class="small"><?php if (($expiredCount ?? 0) > 0): ?><?= (int)$expiredCount ?> dokumen sudah expired.
                <?php endif; ?><?php if (($expiringCount ?? 0) > 0): ?><?= (int)$expiringCount ?> dokumen akan expired dalam
                30 hari.<?php endif; ?>
            </div>
        </div>
        <a href="<?= esc(site_url('tenders')) ?>" class="btn btn-sm btn-outline-dark">
            Tinjau
        </a>
    </div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <div class="datatable-container">
            <table id="tenders-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Dokumen</th>
                        <th>Tanggal Dibuat Dokumen</th>
                        <th>Masa Berlaku Dokumen</th>
                        <th>Status</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>