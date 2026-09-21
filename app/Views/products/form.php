<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php $specs = json_decode($product['technical_specs'] ?? '', true) ?: [['label' => '', 'value' => '']]; ?>
<form method="post" action="<?= esc($action) ?>" enctype="multipart/form-data" class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-box-seam me-2"></i>Data Produk & Datasheet</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label">SKU</label><input name="sku" class="form-control" value="<?= old('sku', $product['sku'] ?? '') ?>"></div>
            <div class="col-md-8"><label class="form-label">Nama Produk *</label><input required name="name" class="form-control" value="<?= old('name', $product['name'] ?? '') ?>"></div>
            <div class="col-md-4"><label class="form-label">Brand</label><input name="brand" class="form-control" value="<?= old('brand', $product['brand'] ?? '') ?>"></div>
            <div class="col-md-4"><label class="form-label">Harga Modal</label><input type="number" step="0.01" name="cost_price" class="form-control" value="<?= old('cost_price', $product['cost_price'] ?? 0) ?>"></div>
            <div class="col-md-4"><label class="form-label">Harga Jual</label><input type="number" step="0.01" name="selling_price" class="form-control" value="<?= old('selling_price', $product['selling_price'] ?? 0) ?>"></div>
            <div class="col-12"><label class="form-label">Deskripsi Produk</label><textarea name="description" class="form-control" rows="4" placeholder="Penjelasan umum produk seperti pada katalog PDF..."><?= old('description', $product['description'] ?? '') ?></textarea></div>
            <div class="col-12"><label class="form-label">Aplikasi / Fungsi Utama</label><textarea name="applications" class="form-control" rows="3" placeholder="Contoh: dapur komersial, laboratorium, pabrik, kendaraan..."><?= old('applications', $product['applications'] ?? '') ?></textarea></div>
            <div class="col-12"><label class="form-label">Sertifikasi / Standar</label><input name="standards" class="form-control" placeholder="Contoh: SNI 7079, CE EN ISO 20345" value="<?= old('standards', $product['standards'] ?? '') ?>"></div>
            <div class="col-12"><label class="form-label">Spesifikasi Teknis</label>
                <div class="form-text mb-2">Buat baris parameter dan spesifikasinya. Contoh: <em>Material Utama → Woven Fiberglass</em>, <em>Kapasitas → 6 KG</em>, atau <em>Ukuran → 47</em>.</div>
                <div id="specifications"><?php foreach ($specs as $spec): ?><div class="row g-2 mb-2 spec-row">
                            <div class="col-md-4"><input name="spec_label[]" class="form-control" placeholder="Parameter (mis. Material Utama)" value="<?= esc($spec['label'] ?? '') ?>"></div>
                            <div class="col-md-7"><input name="spec_value[]" class="form-control" placeholder="Spesifikasi" value="<?= esc($spec['value'] ?? '') ?>"></div>
                            <div class="col-md-1"><button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.spec-row').remove()"><i class="bi bi-trash"></i></button></div>
                        </div><?php endforeach; ?></div><button type="button" class="btn btn-sm btn-outline-primary" onclick="addSpec()"><i class="bi bi-plus-lg"></i> Tambah parameter</button>
            </div>
            <div class="col-12"><label class="form-label">Catatan Datasheet</label><textarea name="datasheet" class="form-control" rows="3" placeholder="Fitur tambahan, catatan teknis, atau informasi lain..."><?= old('datasheet', $product['datasheet'] ?? '') ?></textarea></div>
            <div class="col-md-4"><label class="form-label">Upload Gambar Produk</label><input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="form-control">
                <div class="form-text">JPG, PNG, WEBP maksimal 5 MB.</div><?php if (!empty($product['image_path'])): ?><img src="<?= esc($product['image_path']) ?>" class="img-thumbnail mt-2" style="max-height:120px" alt="Gambar produk"><?php endif; ?>
            </div>
            <div class="col-md-4"><label class="form-label">Upload Video Produk</label><input type="file" name="video" accept="video/mp4,video/webm,video/quicktime" class="form-control">
                <div class="form-text">MP4, WEBM, MOV maksimal 50 MB.</div><?php if (!empty($product['video_path'])): ?><a href="<?= esc($product['video_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">Lihat video tersimpan</a><?php endif; ?>
            </div>
            <div class="col-md-4"><label class="form-label">Upload File Datasheet</label><input type="file" name="datasheet_file" accept="application/pdf" class="form-control">
                <div class="form-text">PDF maksimal 20 MB.</div><?php if (!empty($product['datasheet_file_path'])): ?><a href="<?= esc($product['datasheet_file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2"><i class="bi bi-file-pdf"></i> Buka datasheet PDF</a><?php endif; ?>
            </div>
            <div class="col-md-4"><label class="form-label">Nama Toko</label><input name="store_name" class="form-control" value="<?= old('store_name', $product['store_name'] ?? '') ?>"></div>
            <div class="col-md-4"><label class="form-label">URL Toko Online</label><input type="url" name="store_url" class="form-control" value="<?= old('store_url', $product['store_url'] ?? '') ?>"></div>
            <div class="col-md-4"><label class="form-label">Telepon Toko</label><input name="store_phone" class="form-control" value="<?= old('store_phone', $product['store_phone'] ?? '') ?>"></div>
            <div class="col-md-4"><label class="form-label">PIC Toko</label><input name="store_pic" class="form-control" value="<?= old('store_pic', $product['store_pic'] ?? '') ?>"></div>
            <div class="col-md-4">
                <label class="form-label">Status Produk</label>
                <select name="is_active" class="form-control">
                    <option value="1" <?= old('is_active', $product['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= old('is_active', $product['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer"><a href="/products" class="btn btn-light">Batal</a> <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
</form>
<script>
    function addSpec() {
        const row = document.querySelector('.spec-row').cloneNode(true);
        row.querySelectorAll('input').forEach(input => input.value = '');
        document.querySelector('#specifications').appendChild(row)
    }
</script>
<?= $this->endSection() ?>