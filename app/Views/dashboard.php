<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
            <p class="text-body-secondary mb-0">Kelola workspace e-procurement Anda.</p>
        </div>
        <a href="<?= esc(site_url('quotations/new')) ?>" class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg me-1"></i>Buat Penawaran</a>
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
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
new DataTable('#recent-quotations-table', {
    serverSide: true,
    processing: true,
    responsive: {
        details: {
            type: 'column',
            target: 0
        }
    },
    ajax: {
        url: '<?= esc(site_url('quotations/datatable')) ?>',
        type: 'GET',
        error: function(xhr, textStatus, errorThrown) {
            console.error('Gagal memuat quotation terbaru:', xhr.status, textStatus, errorThrown, xhr.responseText);
        }
    },
    columns: [{
        data: null,
        defaultContent: '',
        className: 'dtr-control',
        responsivePriority: 1,
        orderable: false,
        searchable: false
    }, {
        data: null,
        className: 'text-nowrap',
        responsivePriority: 2,
        orderable: false,
        searchable: false,
        render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }
    }, {
        data: 'quotation_no',
        responsivePriority: 3
    }, {
        data: 'title',
        responsivePriority: 4
    }, {
        data: 'status',
        responsivePriority: 10
    }, {
        data: 'created_at',
        responsivePriority: 10
    }],
    order: [
        [5, 'desc']
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
