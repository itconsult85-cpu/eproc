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
        <div class="table-responsive">
            <table id="quotations-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th class="all"></th>
                        <th class="all">No.</th>
                        <th class="all">Nomor Penawaran</th>
                        <th class="min-tablet">Perusahaan</th>
                        <th class="all">Judul</th>
                        <th class="min-tablet">Total</th>
                        <th class="min-tablet">Status</th>
                        <th class="min-tablet">Dibuat</th>
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
        data: 'quotation_no'
    }, {
        data: 'company_name'
    }, {
        data: 'title'
    }, {
        data: 'grand_total'
    }, {
        data: 'status'
    }, {
        data: 'created_at'
    }, {
        data: 'actions',
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