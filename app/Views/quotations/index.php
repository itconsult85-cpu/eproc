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
<?= $this->section('scripts') ?>
<script>
new DataTable('#quotations-table', {
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
            console.error('Gagal memuat data quotation:', xhr.status, textStatus, errorThrown, xhr
                .responseText);
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
        data: 'company_name',
        responsivePriority: 10
    }, {
        data: 'title',
        responsivePriority: 4
    }, {
        data: 'grand_total',
        responsivePriority: 10
    }, {
        data: 'status',
        responsivePriority: 10
    }, {
        data: 'created_at',
        responsivePriority: 10
    }, {
        data: 'actions',
        responsivePriority: 1,
        orderable: false,
        searchable: false
    }],
    order: [
        [7, 'desc']
    ],
    pageLength: 10,
    language: {
        processing: 'Memuat data...',
        search: 'Cari:',
        lengthMenu: 'Tampilkan _MENU_',
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
        infoEmpty: 'Belum ada data',
        zeroRecords: 'Data tidak ditemukan',
        emptyTable: 'Belum ada penawaran.',
        paginate: {
            first: 'Awal',
            last: 'Akhir',
            next: 'Berikutnya',
            previous: 'Sebelumnya'
        }
    }
});
</script>
<?= $this->endSection() ?>
