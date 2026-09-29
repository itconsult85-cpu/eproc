<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$bast = $bast ?? [];
$selectedSource = ! empty($bast['source_type']) && ! empty($bast['source_id']) ? public_id((int) $bast['source_id']) : '';
$sourceData = ['quotation' => $quotationSources, 'client_purchase_order' => $clientPurchaseOrderSources, 'purchase_order' => $purchaseOrderSources];
?>
<div class="page-intro d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3"><div><h2 class="h5 mb-1"><i class="bi bi-clipboard-check me-2"></i><?= esc($title) ?></h2><p class="text-body-secondary mb-0">Data barang akan disalin sebagai snapshot dari dokumen sumber saat disimpan.</p></div><a href="/basts" class="btn btn-light text-nowrap"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<form method="post" action="<?= esc($action) ?>" class="card form-card" id="bast-form">
    <div class="card-body row g-3"><?= csrf_field() ?>
        <div class="col-md-4"><label class="form-label">Nomor BAST *</label><input name="bast_no" required maxlength="100" class="form-control" value="<?= esc($bast['bast_no'] ?? '') ?>" placeholder="BAST/001/2026"></div>
        <div class="col-md-4"><label class="form-label">Tanggal Serah Terima *</label><input type="date" name="handover_date" required class="form-control" value="<?= esc($bast['handover_date'] ?? date('Y-m-d')) ?>"></div>
        <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="completed" <?= ($bast['status'] ?? 'completed') === 'completed' ? 'selected' : '' ?>>Selesai</option><option value="draft" <?= ($bast['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option></select></div>
        <div class="col-md-4"><label class="form-label">Jenis Sumber *</label><select name="source_type" id="source_type" class="form-select" required><option value="quotation" <?= ($bast['source_type'] ?? 'quotation') === 'quotation' ? 'selected' : '' ?>>Quotation</option><option value="client_purchase_order" <?= ($bast['source_type'] ?? '') === 'client_purchase_order' ? 'selected' : '' ?>>PO IN Klien</option><option value="purchase_order" <?= ($bast['source_type'] ?? '') === 'purchase_order' ? 'selected' : '' ?>>PO OUT Vendor</option></select></div>
        <div class="col-md-8"><label class="form-label">Dokumen Sumber *</label><select name="source_id" id="source_id" class="form-select" required data-searchable-select data-placeholder="Pilih quotation, PO IN, atau PO OUT"></select><div class="form-text" id="source-help">Quotation dan PO IN otomatis mengambil perusahaan; PO OUT memerlukan pilihan perusahaan pelaksana.</div></div>
        <div class="col-md-6" id="company-field"><label class="form-label">Perusahaan Pelaksana *</label><select name="company_id" id="company_id" class="form-select" data-searchable-select data-placeholder="Pilih perusahaan"><option value="">Pilih perusahaan</option><?php foreach ($companies as $company): ?><option value="<?= (int) $company['id'] ?>" <?= (string) ($bast['company_id'] ?? '') === (string) $company['id'] ? 'selected' : '' ?>><?= esc($company['name']) ?></option><?php endforeach; ?></select><div class="form-text">Untuk PO, pilih perusahaan yang melaksanakan tender karena PO lama hanya menyimpan vendor.</div></div>
        <div class="col-md-6"><label class="form-label">Lokasi Serah Terima</label><input name="location" maxlength="180" class="form-control" value="<?= esc($bast['location'] ?? '') ?>" placeholder="Gudang / kantor / lokasi proyek"></div>
        <div class="col-md-6"><label class="form-label">Nama Penerima *</label><input name="recipient_name" required maxlength="160" class="form-control" value="<?= esc($bast['recipient_name'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Jabatan Penerima</label><input name="recipient_position" maxlength="120" class="form-control" value="<?= esc($bast['recipient_position'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Diserahkan Oleh</label><input name="handed_over_by" maxlength="160" class="form-control" value="<?= esc($bast['handed_over_by'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Jabatan Penyerah</label><input name="handed_over_position" maxlength="120" class="form-control" value="<?= esc($bast['handed_over_position'] ?? '') ?>"></div>
        <div class="col-12"><label class="form-label">Catatan</label><textarea name="notes" rows="3" class="form-control"><?= esc($bast['notes'] ?? '') ?></textarea></div>
        <div class="col-12"><div class="border rounded p-3 bg-body-tertiary"><div class="d-flex justify-content-between align-items-center mb-2"><strong>Preview Barang</strong><span id="source-meta" class="small text-body-secondary"></span></div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Barang</th><th>Deskripsi</th><th class="text-end">Qty</th><th>Satuan</th><th class="text-end">Nilai</th></tr></thead><tbody id="source-items"><tr><td colspan="5" class="text-body-secondary">Pilih dokumen sumber untuk melihat item.</td></tr></tbody></table></div></div></div>
    </div><div class="card-footer d-flex justify-content-end gap-2"><a href="/basts" class="btn btn-light">Batal</a><button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan BAST</button></div>
</form>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
(() => {
    const data = <?= json_encode($sourceData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    const currentType = <?= json_encode($bast['source_type'] ?? 'quotation') ?>;
    const currentSource = <?= json_encode($selectedSource) ?>;
    const type = document.querySelector('#source_type'); const source = document.querySelector('#source_id'); const company = document.querySelector('#company_id'); const companyField = document.querySelector('#company-field'); const items = document.querySelector('#source-items'); const meta = document.querySelector('#source-meta');
    function esc(value) { return String(value ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c])); }
    function fillSources() { const typeData = data[type.value] || {}; source.innerHTML = '<option value="">Pilih dokumen sumber</option>'; Object.entries(typeData).forEach(([id, row]) => { const opt = document.createElement('option'); opt.value = id; opt.textContent = row.source_no + ' — ' + row.title + (row.company_name ? ' — ' + row.company_name : (row.vendor_name ? ' — Vendor: ' + row.vendor_name : '')); if (id === currentSource && type.value === currentType) opt.selected = true; source.appendChild(opt); }); if (window.jQuery && jQuery.fn.select2) { jQuery(source).trigger('change.select2'); } render(); }
    function render() { const row = (data[type.value] || {})[source.value]; const companyAuto = type.value === 'quotation' || type.value === 'client_purchase_order'; companyField.style.display = companyAuto ? 'none' : ''; company.required = !companyAuto; if (companyAuto && row) company.value = row.company_id || ''; meta.textContent = row ? row.source_no + ' · ' + (row.date || '-') + (row.company_name ? ' · ' + row.company_name : (row.vendor_name ? ' · Vendor: ' + row.vendor_name : '')) : ''; const list = row?.items || []; items.innerHTML = list.length ? list.map(item => '<tr><td>' + esc(item.product_name) + '</td><td>' + esc(item.description) + '</td><td class="text-end">' + esc(item.quantity) + '</td><td>' + esc(item.unit || 'pcs') + '</td><td class="text-end">Rp ' + Number(item.line_total || 0).toLocaleString('id-ID') + '</td></tr>').join('') : '<tr><td colspan="5" class="text-body-secondary">Dokumen belum memiliki item.</td></tr>'; }
    type.addEventListener('change', () => { fillSources(); }); source.addEventListener('change', render); fillSources();
})();
</script>
<?= $this->endSection() ?>
