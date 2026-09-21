<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<form method="post" action="/quotations" class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-file-earmark-plus me-2"></i>Format Penawaran</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label">Perusahaan penerbit *</label><select name="company_id" required class="form-select">
                    <option value="">Pilih perusahaan</option><?php foreach ($companies as $company): ?><option value="<?= $company['id'] ?>" <?= old('company_id') == $company['id'] ? 'selected' : '' ?>><?= esc($company['name']) ?></option><?php endforeach; ?>
                </select></div>
            <div class="col-md-4"><label class="form-label">Nomor quotation *</label><input required name="quotation_no" class="form-control" value="<?= old('quotation_no', 'Q-' . date('Ymd-His')) ?>"></div>
            <div class="col-md-4"><label class="form-label">Tanggal</label><input type="date" name="issue_date" class="form-control" value="<?= old('issue_date', date('Y-m-d')) ?>"></div>
            <div class="col-md-8"><label class="form-label">Kepada / To</label><input name="customer_name" class="form-control" value="<?= old('customer_name') ?>"></div>
            <div class="col-md-4"><label class="form-label">Attn</label><input name="attention" class="form-control" value="<?= old('attention') ?>"></div>
            <div class="col-12"><label class="form-label">Alamat customer</label><textarea name="customer_address" class="form-control" rows="2"><?= old('customer_address') ?></textarea></div>
            <div class="col-md-4"><label class="form-label">Telepon customer</label><input name="customer_phone" class="form-control"></div>
            <div class="col-md-8"><label class="form-label">About / Perihal *</label><input required name="title" class="form-control" value="<?= old('title') ?>"></div>
            <div class="col-md-3"><label class="form-label">PPN (%)</label><input type="number" step="0.01" name="tax_percent" class="form-control" value="<?= old('tax_percent', $settings['default_tax_percent'] ?? 11) ?>"></div>
            <div class="col-md-3"><label class="form-label">Masa berlaku (hari)</label><input type="number" min="0" name="validity_days" class="form-control" value="<?= old('validity_days', $settings['default_validity_days'] ?? 10) ?>"></div>
            <div class="col-md-3"><label class="form-label">Telepon penandatangan</label><input name="signer_phone" disabled class="form-control" value="<?= esc($settings['signer_phone'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label">Penandatangan</label><input disabled class="form-control" value="<?= esc($settings['signer_name'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Terms of payment</label><input name="payment_terms" class="form-control" value="<?= old('payment_terms', $settings['default_payment_terms'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Delivery</label><input name="delivery_terms" class="form-control" value="<?= old('delivery_terms', $settings['default_delivery_terms'] ?? '') ?>"></div>
            <div class="col-12"><label class="form-label">Item Produk</label>
                <div class="table-responsive">
                    <table class="table align-middle" id="items">
                        <thead>
                            <tr>
                                <th>Brand / Produk</th>
                                <th>Description</th>
                                <th width="90">Qty</th>
                                <th width="80">UoM</th>
                                <th width="140">Price</th>
                                <th width="100">Disc %</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><select name="items[0][product_id]" class="form-select">
                                        <option value="">Pilih produk</option><?php foreach ($products as $product): ?><option value="<?= $product['id'] ?>"><?= esc(($product['brand'] ? $product['brand'] . ' / ' : '') . $product['name']) ?></option><?php endforeach; ?>
                                    </select></td>
                                <td><input name="items[0][description]" class="form-control" placeholder="Deskripsi"></td>
                                <td><input name="items[0][quantity]" type="number" step="0.01" min="0" class="form-control" value="1"></td>
                                <td><input name="items[0][unit]" class="form-control" value="pcs"></td>
                                <td><input name="items[0][unit_price]" type="number" step="0.01" min="0" class="form-control" value="0"></td>
                                <td><input name="items[0][discount_percent]" type="number" step="0.01" min="0" max="100" class="form-control" value="0"></td>
                                <td><button type="button" class="btn btn-outline-secondary" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div><button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()"><i class="bi bi-plus-lg"></i> Tambah item</button>
            </div>
            <div class="col-12"><label class="form-label">Catatan / syarat tambahan</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
        </div>
    </div>
    <div class="card-footer"><a href="/quotations" class="btn btn-light">Batal</a> <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Penawaran</button></div>
</form>
<script>
    let n = 1;

    function addItem() {
        const row = document.querySelector('#items tbody tr').cloneNode(true);
        row.querySelectorAll('[name]').forEach(e => e.name = e.name.replace(/items\[\d+\]/, 'items[' + n + ']'));
        row.querySelectorAll('input').forEach(e => {
            if (e.name.includes('quantity')) e.value = 1;
            if (e.name.includes('unit_price') || e.name.includes('discount')) e.value = 0
        });
        document.querySelector('#items tbody').appendChild(row);
        n++
    }
</script>
<?= $this->endSection() ?>