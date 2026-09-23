<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$isEdit = isset($quotation);
$action = $isEdit ? "/quotations/{$quotation['id']}/update" : "/quotations";
$items = $isEdit ? $quotation['items'] : [[]];
?>
<form method="post" action="<?= $action ?>" class="card card-primary card-outline"
    data-companies="<?= esc(json_encode($companies), 'attr') ?>"
    data-products="<?= esc(json_encode($products), 'attr') ?>" data-item-count="<?= count($items) ?>">
    <?= csrf_field() ?>
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-file-earmark-plus me-2"></i><?= $title ?>
        </h3>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="field-label">
                    Perusahaan penerbit *
                </label>
                <select name="company_id" required class="form-select" data-action="company-change">
                    <option value="">Pilih perusahaan</option>
                    <?php foreach ($companies as $company): ?>
                    <option value="<?= $company['id'] ?>"
                        <?= old('company_id', $quotation['company_id'] ?? '') == $company['id'] ? 'selected' : '' ?>>
                        <?= esc($company['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="field-label">
                    Nomor quotation
                </label>
                <input readonly class="form-control"
                    value="<?= esc($quotation['quotation_no'] ?? 'Otomatis saat disimpan') ?>">
            </div>
            <div class="col-md-4"><label class="field-label">
                    Tanggal
                </label>
                <input type="date" name="issue_date" class="form-control"
                    value="<?= old('issue_date', $quotation['issue_date'] ?? date('Y-m-d')) ?>">
            </div>

            <div class="col-md-8">
                <label class="field-label">
                    Kepada / To
                </label>
                <input readonly name="customer_name" class="form-control"
                    value="<?= old('customer_name', $quotation['customer_name'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="field-label">
                    Attn
                </label>
                <input name="attention" class="form-control"
                    value="<?= old('attention', $quotation['attention'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="field-label">
                    Alamat customer
                </label>
                <textarea readonly name="customer_address" class="form-control" rows="2"><?= old('customer_address', $quotation['customer_address'] ?? '') ?>
                </textarea>
            </div>
            <div class="col-md-4">
                <label class="field-label">
                    Telepon customer
                </label>
                <input name="customer_phone" class="form-control"
                    value="<?= old('customer_phone', $quotation['customer_phone'] ?? '') ?>">
            </div>
            <div class="col-md-8">
                <label class="field-label">
                    About / Perihal *
                </label>
                <input required name="title" class="form-control"
                    value="<?= old('title', $quotation['title'] ?? '') ?>">
            </div>

            <div class="col-md-3">
                <label class="field-label">
                    PPN (%)
                </label>
                <input type="number" step="0.01" name="tax_percent" class="form-control"
                    value="<?= old('tax_percent', $quotation['tax_percent'] ?? $settings['default_tax_percent'] ?? 11) ?>">
            </div>
            <div class="col-md-3"><label class="field-label">
                    Masa berlaku (hari)
                </label>
                <input type="number" min="0" name="validity_days" class="form-control"
                    value="<?= old('validity_days', $quotation['validity_days'] ?? $settings['default_validity_days'] ?? 10) ?>">
            </div>
            <div class="col-md-3">
                <label class="field-label">
                    Telepon penandatangan
                </label>
                <input disabled class="form-control" value="<?= esc($settings['signer_phone'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="field-label">
                    Penandatangan
                </label>
                <input disabled class="form-control" value="<?= esc($settings['signer_name'] ?? '') ?>">
            </div>

            <div class="col-md-6"><label class="field-label">
                    Terms of payment
                </label>
                <input name="payment_terms" class="form-control"
                    value="<?= old('payment_terms', $quotation['payment_terms'] ?? $settings['default_payment_terms'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="field-label">
                    Delivery
                </label>
                <input name="delivery_terms" class="form-control"
                    value="<?= old('delivery_terms', $quotation['delivery_terms'] ?? $settings['default_delivery_terms'] ?? '') ?>">
            </div>

            <div class="col-12">
                <label class="field-label">
                    Item Produk
                </label>
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
                                        data-action="product-change">
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
                                    <button type="button" class="btn btn-outline-secondary" data-action="remove-item">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-action="add-item">
                    <i class="bi bi-plus-lg"></i> Tambah item
                </button>
            </div>
            <div class="col-12">
                <label class="field-label">
                    Catatan / syarat tambahan
                </label>
                <textarea name="notes" class="form-control" rows="3"><?= old('notes', $quotation['notes'] ?? '') ?>
                </textarea>
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

<?= $this->endSection() ?>
