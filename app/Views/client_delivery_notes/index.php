<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header"><div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3"><div><h2 class="h5 mb-1"><i class="bi bi-truck me-2"></i>Surat Jalan Client</h2><p class="text-body-secondary mb-0">Dokumen pengiriman barang kepada klien berdasarkan PO IN.</p></div><?php if (can('client_delivery_note.create')): ?><a href="/client-delivery-notes/new" class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg me-1"></i>Buat Surat Jalan</a><?php endif; ?></div></div>
<div class="alert alert-info"><i class="bi bi-info-circle me-1"></i>Barang dan perusahaan disalin sebagai snapshot dari PO IN klien saat surat jalan disimpan.</div>
<div class="card"><div class="card-body"><div class="datatable-container"><table id="client-delivery-notes-table" class="table table-hover align-middle w-100"><thead><tr><th></th><th>No.</th><th>Nomor Surat Jalan</th><th>PO IN</th><th>Perusahaan</th><th>Tanggal Kirim</th><th>Penerima</th><th>Status</th><th>Dibuat</th><th>Aksi</th></tr></thead></table></div></div></div>
<?= $this->endSection() ?>
