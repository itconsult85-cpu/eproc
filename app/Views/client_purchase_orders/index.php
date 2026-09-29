<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header"><div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3"><div><h2 class="h5 mb-1"><i class="bi bi-file-earmark-arrow-down me-2"></i>PO IN Klien</h2><p class="text-body-secondary mb-0">Catat PO dari klien berdasarkan quotation yang sudah final disetujui.</p></div><?php if (can('client_po.create')): ?><a href="/client-purchase-orders/new" class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg me-1"></i>Buat PO IN</a><?php endif; ?></div></div>
<div class="alert alert-info"><i class="bi bi-info-circle me-1"></i>PO IN menjadi dasar penerimaan barang dan dapat dipilih sebagai sumber BAST.</div>
<div class="card"><div class="card-body"><div class="datatable-container"><table id="client-purchase-orders-table" class="table table-hover align-middle w-100"><thead><tr><th></th><th>No.</th><th>PO Klien</th><th>Quotation</th><th>Perusahaan</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Dibuat</th><th>Aksi</th></tr></thead></table></div></div></div>
<?= $this->endSection() ?>
