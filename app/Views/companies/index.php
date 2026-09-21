<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
    <div>
        <h2 class="h5 mb-1"><i class="bi bi-buildings text-primary me-2"></i>Daftar Perusahaan</h2>
        <p class="text-body-secondary mb-0">Kelola data customer dan PIC untuk kebutuhan quotation.</p>
    </div>
    <a href="/companies/new" class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg me-1"></i>Tambah Perusahaan</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="companies-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>PIC</th>
                        <th>Telepon</th>
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
new DataTable('#companies-table', {
    serverSide: true,
    processing: true,
    ajax: {
        url: '/companies/datatable',
        type: 'GET'
    },
    columns: [{
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
        [0, 'asc']
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