<?= $this->extend('layout') ?><?= $this->section('content') ?><div
    class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Dashboard</h1>
        <p class="text-secondary mb-0">Kelola workspace e-procurement Anda.</p>
    </div><a href="/quotations/new" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Penawaran</a>
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
        <h3 class="card-title">Penawaran Terbaru</h3>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentQuotations as $row): ?>
                <tr>
                    <td><a href="/quotations/<?= $row['id'] ?>"><?= esc($row['quotation_no']) ?></a></td>
                    <td><?= esc($row['title']) ?></td>
                    <td><span class="badge text-bg-secondary"><?= esc($row['status']) ?></span></td>
                    <td><?= esc($row['created_at'] ?? '-') ?></td>
                </tr><?php endforeach; ?><?php if (!$recentQuotations): ?><tr>
                    <td colspan="4" class="text-center text-secondary py-4">Belum ada penawaran.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div><?= $this->endSection() ?>