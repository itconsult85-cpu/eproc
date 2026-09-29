<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h2 class="h5 mb-1"><i class="bi bi-file-earmark-arrow-up me-2"></i><?= esc($title) ?></h2><p class="text-body-secondary mb-0">Simpan satu dokumen beserta identitas dan masa aktifnya. Masa berlaku berlaku untuk dokumen ini, bukan untuk seluruh tender.</p></div>
    <a href="/tenders" class="btn btn-light">Kembali</a>
</div>
<form method="post" action="<?= esc($action) ?>" enctype="multipart/form-data">
    <div class="card mb-3"><div class="card-header"><strong>Identitas Dokumen</strong></div><div class="card-body"><div class="row g-3">
        <div class="col-md-8"><label class="form-label">Nama Dokumen <span class="text-danger">*</span></label><input name="title" class="form-control" required maxlength="220" value="<?= old('title', $tender['title'] ?? '') ?>" placeholder="Contoh: NPWP Perusahaan, Surat Keagenan, Akta Pendirian"></div>
        <div class="col-md-4"><label class="form-label">Nomor Dokumen</label><input name="tender_no" class="form-control" value="<?= old('tender_no', $tender['tender_no'] ?? '') ?>" placeholder="Nomor surat/sertifikat"></div>
        <div class="col-md-4"><label class="form-label">Kategori/Jenis Dokumen</label><input name="procurement_method" class="form-control" value="<?= old('procurement_method', $tender['procurement_method'] ?? '') ?>" placeholder="Administrasi, Legal, Pajak, Teknis"></div>
        <div class="col-md-4"><label class="form-label">Perusahaan Pemilik</label><select name="company_id" class="form-select"><option value="">Pilih perusahaan</option><?php foreach ($companies as $company): ?><option value="<?= $company['id'] ?>" <?= old('company_id', $tender['company_id'] ?? '') == $company['id'] ? 'selected' : '' ?>><?= esc($company['name']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="form-label">Instansi/Penerbit</label><input name="issuer_name" class="form-control" value="<?= old('issuer_name', $tender['issuer_name'] ?? '') ?>" placeholder="Nama instansi penerbit"></div>
        <div class="col-12"><label class="form-label">Keterangan Dokumen</label><textarea name="description" class="form-control" rows="3" placeholder="Keterangan, persyaratan, atau tujuan penggunaan dokumen"><?= old('description', $tender['description'] ?? '') ?></textarea></div>
    </div></div></div>
    <div class="card mb-3"><div class="card-header"><strong>Masa Aktif Dokumen</strong></div><div class="card-body"><div class="row g-3">
        <div class="col-md-4"><label class="form-label">Tanggal Dibuat/Diterbitkan <span class="text-danger">*</span></label><input type="date" name="issue_date" class="form-control" required value="<?= old('issue_date', $tender['issue_date'] ?? date('Y-m-d')) ?>"></div>
        <div class="col-md-4"><label class="form-label">Berlaku Sampai</label><input type="date" name="valid_until" id="valid_until" class="form-control" value="<?= old('valid_until', $tender['valid_until'] ?? '') ?>"><div class="form-text">Isi tanggal terakhir dokumen masih aktif.</div></div>
        <div class="col-md-4 d-flex align-items-end"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="no_expiry" value="1" id="no_expiry" <?= old('no_expiry', empty($tender['valid_until']) && !empty($tender['id']) ? '1' : '') ? 'checked' : '' ?>><label class="form-check-label" for="no_expiry">Dokumen tidak memiliki tanggal kedaluwarsa</label></div></div>
        <div class="col-md-4"><label class="form-label">Status Pengelolaan</label><select name="status" class="form-select"><?php foreach (['draft' => 'Draft', 'open' => 'Aktif', 'closed' => 'Tidak digunakan', 'awarded' => 'Disetujui', 'cancelled' => 'Dibatalkan'] as $value => $label): ?><option value="<?= $value ?>" <?= old('status', $tender['status'] ?? 'draft') === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select><div class="form-text">Kondisi masa aktif dihitung otomatis dari tanggal di atas.</div></div>
    </div></div></div>
    <div class="card mb-3"><div class="card-header"><strong>File dan Informasi Tambahan</strong></div><div class="card-body"><div class="row g-3">
        <div class="col-md-5"><label class="form-label">File Dokumen <span class="text-danger"><?= empty($tender['id']) ? '*' : '' ?></span></label><input type="file" name="tender_file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.jpg,.jpeg,.png" <?= empty($tender['id']) ? 'required' : '' ?>><div class="form-text">PDF, Word, Excel, ZIP, JPG, PNG. Maksimal 50 MB. <?= !empty($tender['original_file_name']) ? 'File saat ini: ' . esc($tender['original_file_name']) : '' ?></div></div>
        <div class="col-md-4"><label class="form-label">Nama Kontak</label><input name="contact_name" class="form-control" value="<?= old('contact_name', $tender['contact_name'] ?? '') ?>"></div>
        <div class="col-md-3"><label class="form-label">Telepon Kontak</label><input name="contact_phone" class="form-control" value="<?= old('contact_phone', $tender['contact_phone'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Email Kontak</label><input type="email" name="contact_email" class="form-control" value="<?= old('contact_email', $tender['contact_email'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Catatan</label><input name="notes" class="form-control" value="<?= old('notes', $tender['notes'] ?? '') ?>"></div>
    </div></div></div>
    <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Dokumen</button>
</form>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?><script>document.addEventListener('DOMContentLoaded',function(){const c=document.getElementById('no_expiry'),d=document.getElementById('valid_until');function sync(){d.disabled=c.checked;if(c.checked)d.value='';}c.addEventListener('change',sync);sync();});</script><?= $this->endSection() ?>
