<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between mb-3">
    <h1>Penawaran</h1>
    <a href="/quotations/new" class="btn btn-primary">Buat Penawaran</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="quotations-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>No.</th>
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
    ajax: {
        url: '<?= esc(site_url('quotations/datatable')) ?>',
        type: 'GET',
        error: function(xhr, textStatus, errorThrown) {
            console.error('Gagal memuat data quotation:', xhr.status, textStatus, errorThrown, xhr.responseText);
        }
    },
    columns: [{
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
        [5, 'desc']
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
