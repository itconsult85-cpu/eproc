<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1"><i class="bi bi-file-earmark-text me-2"></i>Daftar Penawaran</h2>
            <p class="text-body-secondary mb-0">Kelola data penawaran.</p>
        </div>
        <a href="/quotations/new" class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg me-1"></i>Buat
            Penawaran</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="datatable-container">
            <table id="quotations-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th></th>
                        <th>No.</th>
                        <th>Nomor Penawaran</th>
                        <th>Perusahaan</th>
                        <th>Judul</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
