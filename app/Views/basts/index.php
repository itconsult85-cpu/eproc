<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
    <div><h2 class="h5 mb-1"><i class="bi bi-clipboard-check me-2"></i>Berita Acara Serah Terima</h2><p class="text-body-secondary mb-0">Buat dokumen serah terima berdasarkan quotation atau purchase order.</p></div>
    <?php if (can('bast.create')): ?><a href="/basts/new" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat BAST</a><?php endif; ?>
</div>
<div class="card"><div class="card-body"><div class="datatable-container"><table id="basts-table" class="table table-hover align-middle w-100"><thead><tr><th></th><th>No.</th><th>No. BAST</th><th>Sumber</th><th>Perusahaan</th><th>Tanggal Serah Terima</th><th>Penerima</th><th>Status</th><th>Dibuat</th><th>Aksi</th></tr></thead></table></div></div></div>
<?= $this->endSection() ?>
