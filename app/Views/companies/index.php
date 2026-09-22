<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1"><i class="bi bi-buildings text-primary me-2"></i>Daftar Perusahaan</h2>
            <p class="text-body-secondary mb-0">Kelola data customer dan PIC untuk kebutuhan quotation.</p>
        </div>
        <a href="/companies/new" class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg me-1"></i>Tambah
            Perusahaan</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="datatable-container">
            <table id="companies-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th class="all"></th>
                        <th class="all">No.</th>
                        <th class="all">Nama</th>
                        <th class="min-tablet">PIC</th>
                        <th class="min-tablet">Telepon</th>
                        <th class="all">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
new DataTable('#companies-table', {
    serverSide: true,
    processing: true,
    responsive: {
        details: {
            type: 'column',
            target: 0
        }
    },
    ajax: {
        url: '<?= esc(site_url('companies/datatable')) ?>',
        type: 'GET',
        error: function(xhr, textStatus, errorThrown) {
            console.error('Gagal memuat data perusahaan:', xhr.status, textStatus, errorThrown, xhr
                .responseText);
        }
    },
    columns: [{
        data: null,
        defaultContent: '',
        className: 'dtr-control',
        orderable: false,
        searchable: false
    }, {
        data: null,
        className: 'text-nowrap',
        orderable: false,
        searchable: false,
        render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }
    }, {
        data: 'name'
    }, {
        data: 'pic_name'
    }, {
        data: 'phone'
    }, {
        data: 'actions',
        orderable: false,
        searchable: false
    }],
    order: [
        [2, 'asc']
    ],
    pageLength: 10,
    language: {
        processing: 'Memuat data...',
        search: 'Cari:',
        lengthMenu: 'Tampilkan _MENU_',
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
        infoEmpty: 'Belum ada data',
        zeroRecords: 'Data tidak ditemukan',
        emptyTable: 'Belum ada perusahaan.',
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
