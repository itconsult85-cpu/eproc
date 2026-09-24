<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="app-content-header">
    <div class=" page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1">
                <i class="bi bi-box-seam me-2"></i>Daftar Produk
            </h2>
            <p class="text-body-secondary mb-0">
                Kelola katalog, datasheet generik, gambar, dan video produk.
            </p>
        </div>
        <?php if (can('products.create')): ?><a href="/products/new" class="btn btn-primary text-nowrap">
            <i class="bi bi-plus-lg me-1"></i>Tambah Produk
        </a><?php endif; ?>
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
