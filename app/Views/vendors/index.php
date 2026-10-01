<?= $this->extend('layout') ?><?= $this->section('content') ?><div class="app-content-header">
    <div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1">
                <i class="bi bi-truck me-2"></i>
                Vendor
            </h2>
            <p class="text-body-secondary mb-0">
                Kelola pemasok produk dan kebutuhan pengadaan.
            </p>
        </div>
        <?php if (can('vendors.create')): ?>
            <a href="/vendors/new" class="btn btn-primary text-nowrap">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Vendor
            </a>
        <?php endif; ?>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table id="vendors-table" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Vendor</th>
                    <th>PIC</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div><?= $this->endSection() ?>