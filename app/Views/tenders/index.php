<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header"><div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3"><div><h2 class="h5 mb-1"><i class="bi bi-folder2-open me-2"></i>Dokumen Tender</h2><p class="text-body-secondary mb-0">Kelola dokumen tender, jadwal, masa berlaku, dan file pendukung.</p></div><a href="/tenders/new" class="btn btn-primary"><i class="bi bi-cloud-arrow-up me-1"></i>Upload Dokumen Tender</a></div></div>
<div class="card"><div class="card-body"><div class="datatable-container"><table id="tenders-table" class="table table-hover align-middle w-100"><thead><tr><th></th><th>No. Tender</th><th>Judul</th><th>Client/Penerbit</th><th>Diterbitkan</th><th>Berlaku Sampai</th><th>Status</th><th>Aksi</th></tr></thead></table></div></div></div>
<?= $this->endSection() ?>
