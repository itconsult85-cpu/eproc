<?= $this->extend('layout') ?><?= $this->section('content') ?><div class="d-flex justify-content-between mb-3">
    <h1>Penawaran</h1><a href="/quotations/new" class="btn btn-primary">Buat Penawaran</a>
</div>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Perusahaan</th>
                    <th>Judul</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody><?php foreach ($quotations as $row): ?><tr>
                        <td><?= esc($row['quotation_no']) ?></td>
                        <td><?= esc($row['company_name'] ?? '-') ?></td>
                        <td><?= esc($row['title']) ?></td>
                        <td>Rp <?= number_format((float)$row['grand_total'], 0, ',', '.') ?></td>
                        <td><span class="badge text-bg-secondary"><?= esc($row['status']) ?></span></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="/quotations/<?= $row['id'] ?>">Lihat</a>
                            <form class="d-inline" method="post" action="/quotations/<?= $row['id'] ?>/delete" onsubmit="return confirm('Hapus penawaran ini?')"><button class="btn btn-sm btn-outline-danger">Hapus</button></form>
                        </td>
                    </tr><?php endforeach; ?></tbody>
        </table>
    </div>
</div><?= $this->endSection() ?>