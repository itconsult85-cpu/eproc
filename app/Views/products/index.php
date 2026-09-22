<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="app-content-header">
    <div class=" page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1"><i class="bi bi-box-seam me-2"></i>Daftar Produk</h2>
            <p class="text-body-secondary mb-0">Kelola katalog, datasheet generik, gambar, dan video produk.</p>
        </div>
        <a href="/products/new" class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg me-1"></i>Tambah Produk</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="datatable-container">
            <table id="products-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th></th>
                        <th>No.</th>
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
    responsive: {
        details: {
            type: 'column',
            target: 0
        }
    },
    ajax: {
        url: '<?= esc(site_url('products/datatable')) ?>',
        type: 'GET',
        error: function(xhr, textStatus, errorThrown) {
            console.error('Gagal memuat data produk:', xhr.status, textStatus, errorThrown, xhr
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
        data: 'media',
        responsivePriority: 10,
        orderable: false,
        searchable: false
    }, {
        data: 'product',
        responsivePriority: 3
    }, {
        data: 'cost_price',
        responsivePriority: 10
    }, {
        data: 'selling_price',
        responsivePriority: 10
    }, {
        data: 'store',
        responsivePriority: 10
    }, {
        data: 'actions',
        responsivePriority: 1,
        orderable: false,
        searchable: false
    }],
    order: [
        [3, 'asc']
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
