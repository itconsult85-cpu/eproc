<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
    <div>
        <h1><?= esc($quotation['title']) ?></h1>
        <div class="text-secondary"><?= esc($quotation['quotation_no']) ?> &middot; <?= esc($quotation['company_name']) ?></div>
        <span class="badge text-bg-<?= esc(['draft' => 'secondary', 'sent' => 'primary', 'approved' => 'success', 'rejected' => 'danger', 'expired' => 'warning'][$quotation['status']] ?? 'secondary') ?> mt-2">
            <?= esc(ucfirst($quotation['status'])) ?>
        </span>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= esc(site_url('quotations/' . $quotation['id'] . '/edit')) ?>" class="btn btn-warning text-nowrap"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="<?= esc(site_url('quotations/' . $quotation['id'] . '/catalog')) ?>" class="btn btn-outline-success text-nowrap"><i class="bi bi-journal-richtext me-1"></i>Unduh Katalog Produk</a>
        <a href="<?= esc(site_url('quotations/' . $quotation['id'] . '/pdf')) ?>" class="btn btn-primary text-nowrap"><i class="bi bi-file-earmark-pdf me-1"></i>Unduh PDF Quotation</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <p><?= nl2br(esc($quotation['customer_address'] ?? '')) ?></p>
        <div class="table-responsive">
            <table class="table table-hover align-middle quotation-detail-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotation['items'] as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($item['product_name']) ?><br><small><?= esc($item['description'] ?? '') ?></small></td>
                        <td><?= esc($item['quantity']) ?> <?= esc($item['unit']) ?></td>
                        <td>Rp <?= number_format((float) $item['unit_price'], 0, ',', '.') ?></td>
                        <td><?= esc($item['discount_percent']) ?>%</td>
                        <td>Rp <?= number_format((float) $item['line_total'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Subtotal</th>
                        <th>Rp <?= number_format((float) $quotation['subtotal'], 0, ',', '.') ?></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Pajak (<?= esc($quotation['tax_percent']) ?>%)</th>
                        <th>Rp <?= number_format((float) $quotation['tax_amount'], 0, ',', '.') ?></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Grand Total</th>
                        <th>Rp <?= number_format((float) $quotation['grand_total'], 0, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php if ($quotation['notes']): ?>
        <hr>
        <p><strong>Catatan:</strong><br><?= nl2br(esc($quotation['notes'])) ?></p>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
