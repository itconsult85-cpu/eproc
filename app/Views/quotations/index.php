<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1">
                <i class="bi bi-file-earmark-text me-2"></i>Daftar Penawaran
            </h2>
            <p class="text-body-secondary mb-0">
                Pantau pengajuan, negosiasi, persetujuan, dan quotation final dari satu tabel.
            </p>
        </div>
        <a href="/quotations/new" class="btn btn-primary text-nowrap">
            <i class="bi bi-plus-lg me-1"></i>Buat Penawaran
        </a>
    </div>
</div>
<div class="card quotation-workflow-guide mb-3">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <strong class="text-nowrap"><i class="bi bi-signpost-2 me-1"></i>Alur proses:</strong>
            <span class="workflow-step"><span class="workflow-dot bg-secondary"></span>Draft</span>
            <i class="bi bi-chevron-right text-body-secondary"></i>
            <span class="workflow-step"><span class="workflow-dot bg-primary"></span>Terkirim</span>
            <i class="bi bi-chevron-right text-body-secondary"></i>
            <span class="workflow-step"><span class="workflow-dot bg-warning"></span>Negosiasi</span>
            <i class="bi bi-chevron-right text-body-secondary"></i>
            <span class="workflow-step"><span class="workflow-dot bg-success"></span>Final disetujui</span>
        </div>
        <div class="small text-body-secondary mt-2">
            Gunakan tombol <strong>Proses Nego</strong> pada baris quotation untuk membuka detail, mencatat permintaan client,
            melihat riwayat putaran, lalu memilih <strong>Terima &amp; Final</strong> atau <strong>Tolak</strong>.
        </div>
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
                        <th>Tahap Proses</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
