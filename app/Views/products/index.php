<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between mb-3">
    <div>
        <p class="text-secondary mb-0">Kelola katalog, datasheet generik, gambar, dan video produk.</p>
    </div>
    <a href="/products/new" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Produk</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="products-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>Media</th>
                        <th>Produk</th>
                        <th>Harga Modal</th>
                        <th>Harga Jual</th>
                        <th>Toko/PIC</th>
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
new DataTable('#products-table', {
    serverSide: true,
    processing: true,
    ajax: {
        url: '<?= esc(site_url('products/datatable')) ?>',
        type: 'GET',
        error: function(xhr, textStatus, errorThrown) {
            console.error('Gagal memuat data produk:', xhr.status, textStatus, errorThrown, xhr.responseText);
        }
    },
    columns: [{
        data: 'media',
        orderable: false,
        searchable: false
    }, {
        data: 'product'
    }, {
        data: 'cost_price'
    }, {
        data: 'selling_price'
    }, {
        data: 'store'
    }, {
        data: 'actions',
        orderable: false,
        searchable: false
    }],
    order: [
        [1, 'asc']
    ],
    pageLength: 10,
    language: {
        processing: 'Memuat data...',
        search: 'Cari:',
        lengthMenu: 'Tampilkan _MENU_',
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
        infoEmpty: 'Belum ada data',
        zeroRecords: 'Data tidak ditemukan',
        emptyTable: 'Belum ada produk.',
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
