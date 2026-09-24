<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
    <div>
        <h1>
            <?= esc($quotation['title']) ?>
        </h1>
        <div class="text-secondary"><?= esc($quotation['quotation_no']) ?> &middot;
            <?= esc($quotation['company_name']) ?>
        </div>
        <span
            class="badge text-bg-<?= esc(['draft' => 'secondary', 'sent' => 'primary', 'negotiation' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'expired' => 'warning'][$quotation['status']] ?? 'secondary') ?> mt-2">
            <?= esc(ucfirst($quotation['status'])) ?>
        </span>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= esc(site_url('quotations/' . $quotation['id'] . '/edit')) ?>" class="btn btn-warning text-nowrap">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="<?= esc(site_url('quotations/' . $quotation['id'] . '/catalog/preview')) ?>"
            class="btn btn-outline-success text-nowrap">
            <i class="bi bi-journal-richtext me-1"></i>Preview Katalog Produk
        </a>
        <a href="<?= esc(site_url('quotations/' . $quotation['id'] . '/pdf')) ?>" class="btn btn-primary text-nowrap">
            <i class="bi bi-file-earmark-pdf me-1"></i>Unduh PDF Quotation
        </a>
        <?php if ($quotation['status'] === 'approved'): ?><a href="<?= esc(site_url('quotations/' . $quotation['id'] . '/proforma-invoice')) ?>" class="btn btn-success text-nowrap"><i class="bi bi-receipt me-1"></i>Cetak Proforma Invoice</a><?php endif; ?>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <p>
            <?= nl2br(esc($quotation['customer_address'] ?? '')) ?>
        </p>
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
                        <td><?= esc($item['product_name']) ?><br><small><?= esc($item['description'] ?? '') ?></small>
                        </td>
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
        <p>
            <strong>Catatan:</strong><br>
            <?= nl2br(esc($quotation['notes'])) ?>
        </p>
        <?php endif; ?>
    </div>
</div>
<?php if (in_array($quotation['status'], ['draft', 'sent', 'negotiation'], true)): ?>
<div class="card mt-3"><div class="card-header"><strong>Ajukan / Simpan Negosiasi</strong></div><div class="card-body">
    <form method="post" action="<?= esc(site_url('quotations/' . $quotation['id'] . '/negotiations')) ?>" class="row g-2">
        <div class="col-md-6"><label class="form-label">Pesan dari client</label><textarea name="customer_message" class="form-control" rows="2" placeholder="Contoh: minta harga khusus atau perubahan termin"></textarea></div>
        <div class="col-md-6"><label class="form-label">Catatan internal</label><textarea name="internal_notes" class="form-control" rows="2"></textarea></div>
        <div class="col-12"><button class="btn btn-outline-primary" <?= !empty(array_filter($quotation['negotiations'] ?? [], static fn($n) => $n['status'] === 'pending')) ? 'disabled' : '' ?>><i class="bi bi-chat-square-text me-1"></i>Simpan Putaran Negosiasi</button></div>
    </form>
</div></div>
<?php endif; ?>
<?php if (!empty($quotation['negotiations'])): ?>
<div class="card mt-3"><div class="card-header"><strong>Riwayat Negosiasi</strong></div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Putaran</th><th>Status</th><th>Diajukan oleh</th><th>Pesan</th><th>Total</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($quotation['negotiations'] as $negotiation): ?><tr><td><?= esc($negotiation['round_no']) ?></td><td><span class="badge text-bg-<?= $negotiation['status'] === 'accepted' ? 'success' : ($negotiation['status'] === 'rejected' ? 'danger' : 'warning') ?>"><?= esc(ucfirst($negotiation['status'])) ?></span></td><td><?= esc($negotiation['proposed_by']) ?></td><td><?= esc($negotiation['customer_message'] ?: '-') ?></td><td>Rp <?= number_format((float)$negotiation['grand_total'], 0, ',', '.') ?></td><td><?php if ($negotiation['status'] === 'pending'): ?><form method="post" action="<?= esc(site_url('quotations/' . $quotation['id'] . '/negotiations/' . $negotiation['id'] . '/accepted')) ?>" class="d-inline"><button class="btn btn-sm btn-success" onclick="return confirm('Terima sebagai kondisi final?')">Terima & Final</button></form> <form method="post" action="<?= esc(site_url('quotations/' . $quotation['id'] . '/negotiations/' . $negotiation['id'] . '/rejected')) ?>" class="d-inline"><button class="btn btn-sm btn-outline-danger">Tolak</button></form><?php else: ?><?= esc($negotiation['responded_at'] ?? '-') ?><?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>
<?= $this->endSection() ?>
