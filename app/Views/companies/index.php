<?= $this->extend('layout') ?><?= $this->section('content') ?><div class="d-flex justify-content-between mb-3">
    <h1>Perusahaan</h1><a href="/companies/new" class="btn btn-primary">Tambah Perusahaan</a>
</div>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>PIC</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $row): ?>
                <tr>
                    <td><?= esc($row['name']) ?><br><small
                            class="text-secondary"><?= esc($row['email'] ?? '') ?></small></td>
                    <td><?= esc($row['pic_name'] ?? '-') ?></td>
                    <td><?= esc($row['phone'] ?? '-') ?></td>
                    <td><a class="btn btn-sm btn-outline-secondary" href="/companies/<?= $row['id'] ?>/edit">Edit</a>
                        <form class="d-inline" method="post" action="/companies/<?= $row['id'] ?>/delete"
                            onsubmit="return confirm('Hapus perusahaan ini?')">
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr><?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div><?= $this->endSection() ?>