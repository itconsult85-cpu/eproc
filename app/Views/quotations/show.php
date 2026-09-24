<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$workflowLabels = [
    'draft' => 'Draft / Belum diajukan',
    'sent' => 'Terkirim / Menunggu keputusan',
    'negotiation' => 'Sedang negosiasi',
    'approved' => 'Final disetujui',
    'rejected' => 'Ditolak',
    'expired' => 'Kedaluwarsa',
];
?>
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
            <?= esc($workflowLabels[$quotation['status']] ?? ucfirst($quotation['status'])) ?>
        </span>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <?php if (can('quotations.edit')): ?><a href="<?= esc(site_url('quotations/' . public_id($quotation['id']) . '/edit')) ?>" class="btn btn-warning text-nowrap">
            <i class="bi bi-pencil me-1"></i>Edit
        </a><?php endif; ?>
        <?php if (can('quotations.export')): ?>
        <a href="<?= esc(site_url('quotations/' . public_id($quotation['id']) . '/catalog/preview')) ?>"
            class="btn btn-outline-success text-nowrap">
            <i class="bi bi-journal-richtext me-1"></i>Preview Katalog Produk
        </a>
        <a href="<?= esc(site_url('quotations/' . public_id($quotation['id']) . '/pdf')) ?>" class="btn btn-primary text-nowrap">
            <i class="bi bi-file-earmark-pdf me-1"></i>Unduh PDF Quotation
        </a>
        <?php endif; ?>
        <?php if (can('quotations.status') && $quotation['status'] === 'draft' && (empty($quotation['valid_until']) || $quotation['valid_until'] >= date('Y-m-d'))): ?><form method="post" action="<?= esc(site_url('quotations/' . public_id($quotation['id']) . '/status')) ?>" class="d-inline" data-confirm data-confirm-title="Kirim quotation?" data-confirm-message="Quotation akan ditandai sebagai terkirim dan siap diproses lebih lanjut." data-confirm-label="Ya, kirim" data-confirm-variant="primary"><?= csrf_field() ?><input type="hidden" name="status" value="sent"><button class="btn btn-primary text-nowrap"><i class="bi bi-send me-1"></i>Kirim / Tandai Terkirim</button></form><?php endif; ?>
        <?php if (can('quotations.export') && $quotation['status'] === 'approved'): ?><a href="<?= esc(site_url('quotations/' . public_id($quotation['id']) . '/proforma-invoice')) ?>" class="btn btn-success text-nowrap"><i class="bi bi-receipt me-1"></i>Cetak Proforma Invoice</a><?php endif; ?>
    </div>
</div>
<?php $isPastDue = ! empty($quotation['valid_until']) && $quotation['valid_until'] < date('Y-m-d') && in_array($quotation['status'], ['draft', 'sent', 'negotiation', 'expired'], true); ?>
<?php if ($isPastDue): ?>
<div class="alert alert-warning mt-3"><strong>Masa berlaku terlewati.</strong> Quotation tetap dapat digunakan untuk nego atau persetujuan, tetapi sebaiknya perpanjang terlebih dahulu agar tanggal berlaku terdokumentasi. Masa berlaku saat ini: <?= esc($quotation['valid_until']) ?>.
    <?php if (can('quotations.edit') && in_array($quotation['status'], ['draft', 'sent', 'negotiation', 'expired'], true)): ?><form method="post" action="<?= esc(site_url('quotations/' . public_id($quotation['id']) . '/extend-validity')) ?>" class="row g-2 align-items-end mt-2" data-confirm data-confirm-title="Perpanjang masa berlaku?" data-confirm-message="Masa berlaku quotation akan diperpanjang sesuai jumlah hari yang Anda masukkan dan dicatat pada riwayat status." data-confirm-label="Ya, perpanjang" data-confirm-variant="warning"><?= csrf_field() ?><div class="col-auto"><label class="form-label mb-1">Tambahkan masa berlaku</label><div class="input-group"><input type="number" name="validity_days" class="form-control" value="10" min="1" max="3650" required><span class="input-group-text">hari</span></div></div><div class="col-auto"><button class="btn btn-warning">Perpanjang & Catat</button></div></form><?php endif; ?>
</div>
<?php endif; ?>
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
<?php if (can('quotations.status') && in_array($quotation['status'], ['draft', 'sent', 'negotiation'], true)): ?>
<?php $hasPendingNegotiation = ! empty(array_filter($quotation['negotiations'] ?? [], static fn($n) => $n['status'] === 'pending')); ?>
<?php $negotiationItems = old('items'); $negotiationItems = is_array($negotiationItems) && $negotiationItems !== [] ? $negotiationItems : $quotation['items']; ?>
<div class="card mt-3"><div class="card-header"><strong><i class="bi bi-chat-square-text me-1"></i>Proses Negosiasi Quotation</strong></div><div class="card-body">
    <p class="text-body-secondary small mb-3">
        Ubah nilai yang diminta client pada editor di bawah. Perubahan disimpan sebagai <strong>versi usulan</strong> dan belum mengubah quotation utama sampai dipilih
        <strong>Terima &amp; Final</strong> pada riwayat negosiasi.
    </p>
    <?php if ($hasPendingNegotiation): ?><div class="alert alert-warning py-2"><i class="bi bi-hourglass-split me-1"></i>Masih ada putaran negosiasi yang menunggu keputusan. Putaran baru dapat dibuat setelah putaran tersebut diterima atau ditolak.</div><?php endif; ?>
    <form method="post" action="<?= esc(site_url('quotations/' . public_id($quotation['id']) . '/negotiations')) ?>" data-negotiation-editor data-confirm data-confirm-title="Simpan putaran negosiasi?" data-confirm-message="Perubahan item dan syarat akan disimpan sebagai versi usulan negosiasi." data-confirm-label="Ya, simpan usulan" data-confirm-variant="primary">
        <?= csrf_field() ?>
        <fieldset <?= $hasPendingNegotiation ? 'disabled' : '' ?>>
            <div class="table-responsive mb-3">
                <table class="table table-sm align-middle negotiation-items-table" id="negotiation-items">
                    <thead><tr><th>No.</th><th>Produk</th><th>Deskripsi</th><th>Qty</th><th>Satuan</th><th>Harga</th><th>Disc %</th><th>Total</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($negotiationItems as $i => $item): ?><tr class="negotiation-item-row">
                        <td class="negotiation-row-number"><?= $i + 1 ?></td>
                        <td><select name="items[<?= $i ?>][product_id]" class="form-select form-select-sm" data-action="negotiation-product-change" required><option value="">Pilih produk</option><?php foreach (($products ?? []) as $product): ?><option value="<?= (int) $product['id'] ?>" data-price="<?= esc($product['selling_price'] ?? 0) ?>" <?= (int) $product['id'] === (int) ($item['product_id'] ?? 0) ? 'selected' : '' ?>><?= esc(($product['brand'] ? $product['brand'] . ' / ' : '') . $product['name']) ?></option><?php endforeach; ?></select></td>
                        <td><input name="items[<?= $i ?>][description]" class="form-control form-control-sm" value="<?= esc($item['description'] ?? '') ?>"></td>
                        <td><input name="items[<?= $i ?>][quantity]" type="number" step="0.01" min="0.01" class="form-control form-control-sm negotiation-number" value="<?= esc($item['quantity'] ?? 1) ?>" required></td>
                        <td><input name="items[<?= $i ?>][unit]" class="form-control form-control-sm" value="<?= esc($item['unit'] ?? 'pcs') ?>" required></td>
                        <td><input name="items[<?= $i ?>][unit_price]" type="number" step="0.01" min="0" class="form-control form-control-sm negotiation-number" value="<?= esc($item['unit_price'] ?? 0) ?>" required></td>
                        <td><input name="items[<?= $i ?>][discount_percent]" type="number" step="0.01" min="0" max="100" class="form-control form-control-sm negotiation-number" value="<?= esc($item['discount_percent'] ?? 0) ?>" required></td>
                        <td class="text-end text-nowrap"><span data-negotiation-line-total>Rp 0</span></td>
                        <td><button type="button" class="btn btn-sm btn-outline-danger" data-action="remove-negotiation-item" title="Hapus item"><i class="bi bi-trash3"></i></button></td>
                    </tr><?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary mb-3" data-action="add-negotiation-item"><i class="bi bi-plus-lg me-1"></i>Tambah item</button>
            <div class="row g-2 mb-3">
                <div class="col-md-4"><label class="form-label">Pajak (%)</label><input name="tax_percent" type="number" step="0.01" min="0" max="100" class="form-control" value="<?= esc($quotation['tax_percent'] ?? 0) ?>" required></div>
                <div class="col-md-4"><label class="form-label">Termin pembayaran</label><input name="payment_terms" class="form-control" value="<?= esc($quotation['payment_terms'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label">Termin pengiriman</label><input name="delivery_terms" class="form-control" value="<?= esc($quotation['delivery_terms'] ?? '') ?>"></div>
                <div class="col-12"><label class="form-label">Catatan / syarat tambahan</label><textarea name="notes" class="form-control" rows="2"><?= esc($quotation['notes'] ?? '') ?></textarea></div>
            </div>
            <div class="negotiation-total-preview text-end mb-3"><span>Subtotal: <strong data-negotiation-subtotal>Rp 0</strong></span><span class="ms-3">Pajak: <strong data-negotiation-tax>Rp 0</strong></span><span class="ms-3">Grand total: <strong data-negotiation-grand-total>Rp 0</strong></span></div>
            <div class="row g-2"><div class="col-md-6"><label class="form-label">Pesan dari client</label><textarea name="customer_message" class="form-control" rows="2" placeholder="Contoh: minta harga khusus atau perubahan termin"></textarea></div><div class="col-md-6"><label class="form-label">Catatan internal</label><textarea name="internal_notes" class="form-control" rows="2"></textarea></div></div>
            <button class="btn btn-primary mt-3" type="submit"><i class="bi bi-chat-square-text me-1"></i>Simpan Usulan Negosiasi</button>
        </fieldset>
    </form>
</div></div>
<?php endif; ?>
<?php if (!empty($quotation['negotiations'])): ?>
<div class="card mt-3"><div class="card-header"><strong>Riwayat Negosiasi</strong></div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Putaran</th><th>Status</th><th>Diajukan oleh</th><th>Pesan</th><th>Total</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($quotation['negotiations'] as $negotiation): ?><tr><td><?= esc($negotiation['round_no']) ?></td><td><span class="badge text-bg-<?= $negotiation['status'] === 'accepted' ? 'success' : ($negotiation['status'] === 'rejected' ? 'danger' : 'warning') ?>"><?= esc(ucfirst($negotiation['status'])) ?></span></td><td><?= esc($negotiation['proposed_by']) ?></td><td><?= esc($negotiation['customer_message'] ?: '-') ?></td><td>Rp <?= number_format((float)$negotiation['grand_total'], 0, ',', '.') ?></td><td><?php if ($negotiation['status'] === 'pending' && can('quotations.status')): ?><form method="post" action="<?= esc(site_url('quotations/' . public_id($quotation['id']) . '/negotiations/' . public_id($negotiation['id']) . '/accepted')) ?>" class="d-inline" data-confirm data-confirm-title="Terima sebagai kondisi final?" data-confirm-message="Kondisi negosiasi ini akan diterapkan sebagai nilai final quotation dan statusnya menjadi disetujui." data-confirm-label="Ya, terima & final" data-confirm-variant="success"><?= csrf_field() ?><button class="btn btn-sm btn-success">Terima & Final</button></form> <form method="post" action="<?= esc(site_url('quotations/' . public_id($quotation['id']) . '/negotiations/' . public_id($negotiation['id']) . '/rejected')) ?>" class="d-inline" data-confirm data-confirm-title="Tolak putaran negosiasi?" data-confirm-message="Putaran negosiasi ini akan ditandai sebagai ditolak dan quotation dikembalikan ke status terkirim." data-confirm-label="Ya, tolak" data-confirm-variant="danger"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger">Tolak</button></form><?php else: ?><?= esc($negotiation['responded_at'] ?? '-') ?><?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>
<?= $this->endSection() ?>
