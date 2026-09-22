<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$isEdit = isset($quotation);
$action = $isEdit ? "/quotations/{$quotation['id']}/update" : "/quotations";
$items = $isEdit ? $quotation['items'] : [[]];
?>
<form method="post" action="<?= $action ?>" class="card card-primary card-outline"><?= csrf_field() ?>
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-file-earmark-plus me-2"></i><?= $title ?></h3>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="field-label">Perusahaan penerbit *</label>
                <select name="company_id" required class="form-select" onchange="autoFillCompany(this)">
                    <option value="">Pilih perusahaan</option>
                    <?php foreach ($companies as $company): ?>
                    <option value="<?= $company['id'] ?>"
                        <?= old('company_id', $quotation['company_id'] ?? '') == $company['id'] ? 'selected' : '' ?>>
                        <?= esc($company['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4"><label class="field-label">Nomor quotation *</label><input required name="quotation_no"
                    class="form-control"
                    value="<?= old('quotation_no', $quotation['quotation_no'] ?? 'Q-' . date('Ymd-His')) ?>">
            </div>
            <div class="col-md-4"><label class="field-label">Tanggal</label>
                <input type="date" name="issue_date" class="form-control"
                    value="<?= old('issue_date', $quotation['issue_date'] ?? date('Y-m-d')) ?>">
            </div>

            <div class="col-md-8"><label class="field-label">Kepada / To</label>
                <input disabled name="customer_name" class="form-control"
                    value="<?= old('customer_name', $quotation['customer_name'] ?? '') ?>">
            </div>
            <div class="col-md-4"><label class="field-label">Attn</label>
                <input name="attention" class="form-control"
                    value="<?= old('attention', $quotation['attention'] ?? '') ?>">
            </div>
            <div class="col-12"><label class="field-label">Alamat customer</label>
                <textarea disabled name="customer_address" class="form-control"
                    rows="2"><?= old('customer_address', $quotation['customer_address'] ?? '') ?></textarea>
            </div>
            <div class="col-md-4"><label class="field-label">Telepon customer</label>
                <input name="customer_phone" class="form-control"
                    value="<?= old('customer_phone', $quotation['customer_phone'] ?? '') ?>">
            </div>
            <div class="col-md-8"><label class="field-label">About / Perihal *</label>
                <input required name="title" class="form-control"
                    value="<?= old('title', $quotation['title'] ?? '') ?>">
            </div>

            <div class="col-md-3"><label class="field-label">PPN (%)</label>
                <input type="number" step="0.01" name="tax_percent" class="form-control"
                    value="<?= old('tax_percent', $quotation['tax_percent'] ?? $settings['default_tax_percent'] ?? 11) ?>">
            </div>
            <div class="col-md-3"><label class="field-label">Masa berlaku (hari)</label>
                <input type="number" min="0" name="validity_days" class="form-control"
                    value="<?= old('validity_days', $quotation['validity_days'] ?? $settings['default_validity_days'] ?? 10) ?>">
            </div>
            <div class="col-md-3"><label class="field-label">Telepon penandatangan</label>
                <input disabled class="form-control" value="<?= esc($settings['signer_phone'] ?? '') ?>">
            </div>
            <div class="col-md-3"><label class="field-label">Penandatangan</label>
                <input disabled class="form-control" value="<?= esc($settings['signer_name'] ?? '') ?>">
            </div>

            <div class="col-md-6"><label class="field-label">Terms of payment</label>
                <input name="payment_terms" class="form-control"
                    value="<?= old('payment_terms', $quotation['payment_terms'] ?? $settings['default_payment_terms'] ?? '') ?>">
            </div>
            <div class="col-md-6"><label class="field-label">Delivery</label>
                <input name="delivery_terms" class="form-control"
                    value="<?= old('delivery_terms', $quotation['delivery_terms'] ?? $settings['default_delivery_terms'] ?? '') ?>">
            </div>

            <div class="col-12"><label class="field-label">Item Produk</label>
                <div class="table-responsive">
                    <table class="table align-middle quotation-items-table" id="items">
                        <thead>
                            <tr>
                                <th width="55">No.</th>
                                <th width="200">Brand / Produk</th>
                                <th>Description</th>
                                <th width="90">Qty</th>
                                <th width="80">UoM</th>
                                <th width="140">Price</th>
                                <th width="90">Disc %</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $i => $it): ?>
                            <tr>
                                <td class="item-row-number text-center"><?= $i + 1 ?></td>
                                <td>
                                    <?php
                                        // Cari path gambar untuk produk yang sudah tersimpan (saat edit)
                                        $productImagePath = '';
                                        if (!empty($it['product_id'])) {
                                            foreach ($products as $p) {
                                                if ($p['id'] == $it['product_id']) {
                                                    $productImagePath = $p['image_path'] ?? '';
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                    <select name="items[<?= $i ?>][product_id]" class="form-select mb-1"
                                        onchange="autoFillProduct(this)">
                                        <option value="">Pilih produk</option>
                                        <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['id'] ?>"
                                            <?= ($it['product_id'] ?? '') == $product['id'] ? 'selected' : '' ?>>
                                            <?= esc(($product['brand'] ? $product['brand'] . ' / ' : '') . $product['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <img class="product-thumb img-thumbnail"
                                        style="max-height:60px; display:<?= $productImagePath ? 'block' : 'none' ?>;"
                                        src="<?= esc($productImagePath) ?>">
                                </td>
                                <td>
                                    <input name="items[<?= $i ?>][description]" class="form-control"
                                        placeholder="Deskripsi" value="<?= esc($it['description'] ?? '') ?>">
                                </td>
                                <td>
                                    <input name="items[<?= $i ?>][quantity]" type="number" step="0.01" min="0"
                                        class="form-control" value="<?= $it['quantity'] ?? 1 ?>">
                                </td>
                                <td>
                                    <input name="items[<?= $i ?>][unit]" class="form-control"
                                        value="<?= $it['unit'] ?? 'pcs' ?>">
                                </td>
                                <td>
                                    <input name="items[<?= $i ?>][unit_price]" type="number" step="0.01" min="0"
                                        class="form-control" value="<?= $it['unit_price'] ?? 0 ?>">
                                </td>
                                <td>
                                    <input name="items[<?= $i ?>][discount_percent]" type="number" step="0.01" min="0"
                                        max="100" class="form-control" value="<?= $it['discount_percent'] ?? 0 ?>">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="removeItem(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">
                    <i class="bi bi-plus-lg"></i> Tambah item
                </button>
            </div>
            <div class="col-12">
                <label class="field-label">Catatan / syarat tambahan</label>
                <textarea name="notes" class="form-control"
                    rows="3"><?= old('notes', $quotation['notes'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="/quotations" class="btn btn-light">
            Batal
        </a>
        <button class="btn btn-primary"><i class="bi bi-save me-1"></i>
            Simpan Penawaran
        </button>
    </div>
</form>

<script>
const companiesData = <?= json_encode($companies) ?>;
const productsData = <?= json_encode($products) ?>;
let n = <?= count($items) ?>;

function autoFillCompany(select) {
    const nameInput = document.querySelector('[name="customer_name"]');
    const addressInput = document.querySelector('[name="customer_address"]');

    if (select.value) {
        const company = companiesData.find(c => c.id == select.value);
        if (company) {
            nameInput.value = company.name || '';
            addressInput.value = company.address || '';
            document.querySelector('[name="customer_phone"]').value = company.phone || '';
            document.querySelector('[name="attention"]').value = company.pic_name || '';

            // Kunci (readonly) input nama dan alamat perusahaan agar tidak bisa diedit
            nameInput.readOnly = true;
            addressInput.readOnly = true;
        }
    } else {
        // Jika memilih "Pilih perusahaan" (kosong), buka kembali form
        nameInput.readOnly = false;
        addressInput.readOnly = false;
        nameInput.value = '';
        addressInput.value = '';
    }
}

function autoFillProduct(select) {
    const product = productsData.find(p => p.id == select.value);
    const tr = select.closest('tr');
    if (product) {
        // Deskripsi dikosongkan agar bisa diisi manual sesuai data tender
        tr.querySelector('[name$="[description]"]').value = '';
        tr.querySelector('[name$="[unit_price]"]').value = product.selling_price || 0;

        const img = tr.querySelector('.product-thumb');
        if (product.image_path) {
            img.src = product.image_path;
            img.style.display = 'block';
        } else {
            img.style.display = 'none';
        }
    }
}

function addItem() {
    const row = document.querySelector('#items tbody tr:last-child').cloneNode(true);
    row.querySelectorAll('[name]').forEach(e => e.name = e.name.replace(/items\[\d+\]/, 'items[' + n + ']'));
    row.querySelectorAll('input').forEach(e => {
        if (e.name.includes('quantity')) e.value = 1;
        else if (e.name.includes('unit_price') || e.name.includes('discount')) e.value = 0;
        else e.value = '';
    });
    row.querySelector('.product-thumb').style.display = 'none';
    document.querySelector('#items tbody').appendChild(row);
    n++;
    renumberItems();
}

function removeItem(button) {
    const rows = document.querySelectorAll('#items tbody tr');
    if (rows.length > 1) {
        button.closest('tr').remove();
    } else {
        button.closest('tr').querySelectorAll('input').forEach(input => input.value = '');
        button.closest('tr').querySelector('select').value = '';
        button.closest('tr').querySelector('.product-thumb').style.display = 'none';
    }
    renumberItems();
}

function renumberItems() {
    document.querySelectorAll('#items tbody .item-row-number').forEach((cell, index) => {
        cell.textContent = index + 1;
    });
}

// Jalankan pengecekan saat halaman edit pertama kali diload
window.onload = function() {
    const companySelect = document.querySelector('[name="company_id"]');
    if (companySelect.value) {
        autoFillCompany(companySelect);
    }
};
</script>
<?= $this->endSection() ?>
