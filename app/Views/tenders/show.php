<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$fileUrl = ! empty($tender['file_path']) ? site_url('files/tenders/' . public_id((int) $tender['id'])) : null;
$fileMime = strtolower((string) ($tender['file_mime'] ?? ''));
$isImage = str_starts_with($fileMime, 'image/');
$isPdf = $fileMime === 'application/pdf' || strtolower(pathinfo((string) ($tender['original_file_name'] ?? ''), PATHINFO_EXTENSION)) === 'pdf';
$publicId = public_id((int) $tender['id']);
?>
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
    <div><h2 class="h5 mb-1"><?= esc($tender['title']) ?></h2><div class="text-body-secondary"><?= esc($tender['tender_no'] ?: 'Tanpa nomor tender') ?></div></div>
    <div class="d-flex gap-2"><a href="<?= site_url('tenders') ?>" class="btn btn-light">Kembali</a><a href="<?= site_url('tenders/' . $publicId . '/edit') ?>" class="btn btn-warning">Edit</a></div>
</div>
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card h-100"><div class="card-header"><strong>Informasi Dokumen</strong></div><div class="card-body"><div class="row g-3">
            <div class="col-md-6"><strong>Instansi/Penerbit</strong><br><?= esc($tender['issuer_name'] ?: '-') ?></div>
            <div class="col-md-6"><strong>Kategori/Jenis</strong><br><?= esc($tender['procurement_method'] ?: '-') ?></div>
            <div class="col-md-6"><strong>Tanggal Diterbitkan</strong><br><?= esc($tender['issue_date'] ?: '-') ?></div>
            <div class="col-md-6"><strong>Masa Berlaku</strong><br><?= esc($tender['valid_until'] ?: 'Tidak memiliki tanggal kedaluwarsa') ?></div>
            <div class="col-md-6"><strong>Status</strong><br><?= esc(ucfirst($tender['status'])) ?></div>
            <div class="col-md-6"><strong>Kontak</strong><br><?= esc($tender['contact_name'] ?: '-') ?><br><?= esc(trim(($tender['contact_email'] ?: '') . ' ' . ($tender['contact_phone'] ?: ''))) ?></div>
            <div class="col-12"><hr><strong>Keterangan Dokumen</strong><p><?= nl2br(esc($tender['description'] ?: '-')) ?></p><strong>Catatan</strong><p class="mb-0"><?= nl2br(esc($tender['notes'] ?: '-')) ?></p></div>
        </div></div></div>
    </div>
    <div class="col-lg-5">
        <div class="card"><div class="card-header d-flex justify-content-between align-items-center"><strong>File Dokumen</strong><?php if ($fileUrl): ?><span class="small text-body-secondary"><?= esc($tender['original_file_name'] ?: 'Dokumen') ?></span><?php endif; ?></div><div class="card-body">
            <?php if (! $fileUrl): ?>
                <div class="text-center text-body-secondary py-5"><i class="bi bi-file-earmark-x fs-1 d-block mb-2"></i>Belum ada file dokumen.</div>
            <?php elseif ($isImage): ?>
                <img src="<?= esc($fileUrl) ?>" alt="<?= esc($tender['original_file_name'] ?: $tender['title']) ?>" class="img-fluid rounded border d-block mx-auto" style="max-height:70vh;object-fit:contain">
            <?php elseif ($isPdf): ?>
                <iframe src="<?= esc($fileUrl) ?>" title="<?= esc($tender['original_file_name'] ?: $tender['title']) ?>" class="w-100 border rounded" style="height:70vh"></iframe>
            <?php else: ?>
                <div class="text-center py-5"><i class="bi bi-file-earmark-text fs-1 d-block mb-2 text-primary"></i><p class="mb-2">Preview browser tidak tersedia untuk tipe file ini.</p><a href="<?= esc($fileUrl) ?>" target="_blank" rel="noopener" class="btn btn-outline-primary"><i class="bi bi-download me-1"></i>Buka atau Unduh File</a></div>
            <?php endif; ?>
        </div></div>
    </div>
</div>
<?= $this->endSection() ?>
