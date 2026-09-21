<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
            <p class="text-body-secondary mb-0">Kelola workspace e-procurement Anda.</p>
        </div>
        <a href="/quotations/new" class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg me-1"></i>Buat
            Penawaran</a>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-bg-primary">
            <div class="card-body">
                <div class="fs-1 fw-bold"><?= $companyCount ?></div>
                <div>Perusahaan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success">
            <div class="card-body">
                <div class="fs-1 fw-bold"><?= $productCount ?></div>
                <div>Produk Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-dark">
            <div class="card-body">
                <div class="fs-1 fw-bold"><?= $quotationCount ?></div>
                <div>Total Penawaran</div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Penawaran Terbaru</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="recent-quotations-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
new DataTable('#recent-quotations-table', {
    serverSide: true,
    processing: true,
    ajax: {
        url: '/quotations/datatable',
        type: 'GET'
    },
    columns: [{
        data: 'quotation_no',
        render: (data, type, row) => type === 'display' ? '<a href="/quotations/' + row.id + '">' +
            data + '</a>' : data
    }, {
        data: 'title'
    }, {
        data: 'status'
    }, {
        data: 'created_at'
    }],
    order: [
        [3, 'desc']
    ],
    pageLength: 5,
    lengthChange: false,
    searching: false,
    language: {
        processing: 'Memuat data...',
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
        infoEmpty: 'Belum ada data',
        zeroRecords: 'Belum ada penawaran.',
        emptyTable: 'Belum ada penawaran.',
        paginate: {
            next: 'Berikutnya',
            previous: 'Sebelumnya'
        }
    }
});
</script>
<?= $this->endSection() ?>