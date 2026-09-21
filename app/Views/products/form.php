<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<form method="post" action="<?= esc($action) ?>" enctype="multipart/form-data" class="card card-primary card-outline">
    <div class="card-header"><h3 class="card-title">Data Produk & Media</h3></div>
    <div class="card-body"><div class="row g-3">
        <div class="col-md-4"><label class="form-label">SKU</label><input name="sku" class="form-control" value="<?= old('sku', $product['sku'] ?? '') ?>"></div>
        <div class="col-md-8"><label class="form-label">Nama Produk *</label><input required name="name" class="form-control" value="<?= old('name', $product['name'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Brand</label><input name="brand" class="form-control" value="<?= old('brand', $product['brand'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Harga Modal</label><input type="number" step="0.01" name="cost_price" class="form-control" value="<?= old('cost_price', $product['cost_price'] ?? 0) ?>"></div>
        <div class="col-md-4"><label class="form-label">Harga Jual</label><input type="number" step="0.01" name="selling_price" class="form-control" value="<?= old('selling_price', $product['selling_price'] ?? 0) ?>"></div>
        <div class="col-12"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control"><?= old('description', $product['description'] ?? '') ?></textarea></div>
        <div class="col-12"><label class="form-label">Datasheet</label><textarea name="datasheet" class="form-control" rows="4"><?= old('datasheet', $product['datasheet'] ?? '') ?></textarea></div>
        <div class="col-md-6"><label class="form-label">URL Gambar</label><input type="url" name="image_url" class="form-control mb-2" value="<?= old('image_url', $product['image_url'] ?? '') ?>"><label class="form-label">Upload Gambar</label><input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="form-control"><div class="form-text">JPG, PNG, WEBP maksimal 5 MB.</div><?php if (!empty($product['image_path'])): ?><img src="<?= esc($product['image_path']) ?>" class="img-thumbnail mt-2" style="max-height:120px" alt="Gambar produk"><?php endif; ?></div>
        <div class="col-md-6"><label class="form-label">URL Video</label><input type="url" name="video_url" class="form-control mb-2" value="<?= old('video_url', $product['video_url'] ?? '') ?>"><label class="form-label">Upload Video</label><input type="file" name="video" accept="video/mp4,video/webm,video/quicktime" class="form-control"><div class="form-text">MP4, WEBM, MOV maksimal 50 MB.</div><?php if (!empty($product['video_path'])): ?><a href="<?= esc($product['video_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">Lihat video tersimpan</a><?php endif; ?></div>
        <div class="col-md-4"><label class="form-label">Nama Toko</label><input name="store_name" class="form-control" value="<?= old('store_name', $product['store_name'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">URL Toko Online</label><input type="url" name="store_url" class="form-control" value="<?= old('store_url', $product['store_url'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Telepon Toko</label><input name="store_phone" class="form-control" value="<?= old('store_phone', $product['store_phone'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">PIC Toko</label><input name="store_pic" class="form-control" value="<?= old('store_pic', $product['store_pic'] ?? '') ?>"></div>
    </div></div>
    <div class="card-footer"><a href="/products" class="btn btn-light">Batal</a> <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
</form>
<?= $this->endSection() ?>
