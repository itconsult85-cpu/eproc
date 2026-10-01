<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Jalan <?= esc($delivery['delivery_no']) ?></title>
    <style>
        @page {
            margin: 28px 35px
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #202124;
            font-size: 11px;
            line-height: 1.45
        }

        h1 {
            font-size: 18px;
            text-align: center;
            margin: 0 0 4px;
            text-transform: uppercase
        }

        .company {
            text-align: center;
            font-size: 12px;
            margin-bottom: 18px
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0
        }

        .meta td {
            padding: 3px 0;
            vertical-align: top
        }

        .meta td:first-child {
            width: 150px;
            font-weight: bold
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0
        }

        .items th,
        .items td {
            border: 1px solid #555;
            padding: 6px
        }

        .items th {
            background: #e9ecef;
            text-align: center
        }

        .items .num {
            text-align: center;
            width: 28px
        }

        .items .qty {
            text-align: right;
            white-space: nowrap
        }

        .signatures {
            width: 100%;
            border-collapse: collapse;
            margin-top: 42px
        }

        .signatures td {
            text-align: center;
            width: 50%;
            vertical-align: top
        }

        .signature-space {
            height: 65px
        }

        .small {
            font-size: 9px;
            color: #555
        }

        .line {
            border-bottom: 1px solid #222;
            display: inline-block;
            min-width: 170px
        }
    </style>
</head>

<body>
    <h1>Surat Jalan</h1>
    <div class="company">
        <strong><?= esc($delivery['company_name'] ?? '-') ?></strong><br><?= esc($delivery['company_address'] ?? '') ?><?= ! empty($delivery['company_phone']) ? ' · ' . esc($delivery['company_phone']) : '' ?>
    </div>
    <table class="meta">
        <tr>
            <td>Nomor Surat Jalan</td>
            <td>: <?= esc($delivery['delivery_no']) ?></td>
        </tr>
        <tr>
            <td>Nomor PO IN Klien</td>
            <td>: <?= esc($delivery['client_po_no'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>Tanggal Pengiriman</td>
            <td>: <?= esc($delivery['delivery_date']) ?></td>
        </tr>
        <tr>
            <td>Tujuan Pengiriman</td>
            <td>: <?= esc($delivery['destination'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>Penerima</td>
            <td>:
                <?= esc($delivery['recipient_name']) ?><?= ! empty($delivery['recipient_position']) ? ' — ' . esc($delivery['recipient_position']) : '' ?>
            </td>
        </tr>
    </table>
    <table class="items">
        <thead>
            <tr>
                <th class="num">No.</th>
                <th>Nama Barang</th>
                <th>Deskripsi</th>
                <th>Qty</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (($delivery['items'] ?? []) as $index => $item): ?>
                <tr>
                    <td class="num"><?= $index + 1 ?></td>
                    <td><?= esc($item['product_name'] ?? '-') ?></td>
                    <td><?= nl2br(esc($item['description'] ?? '-')) ?></td>
                    <td class="qty"><?= esc($item['quantity'] ?? 0) ?></td>
                    <td><?= esc($item['unit'] ?? 'pcs') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (! empty($delivery['notes'])): ?>
        <p><strong>Catatan:</strong><br><?= nl2br(esc($delivery['notes'])) ?></p>
    <?php endif; ?>
    <p>Barang telah diserahkan sesuai rincian di atas.</p>
    <table class="signatures">
        <tr>
            <td>Yang Menyerahkan,<div class="signature-space"></div><strong
                    class="line"><?= esc($delivery['delivered_by'] ?: '-') ?></strong>
                <?php if (! empty($delivery['delivered_position'])): ?><br><?= esc($delivery['delivered_position']) ?>
                <?php endif; ?>
            </td>
            <td>Yang Menerima,<div class="signature-space"></div><strong
                    class="line"><?= esc($delivery['recipient_name']) ?></strong>
                <?php if (! empty($delivery['recipient_position'])): ?><br><?= esc($delivery['recipient_position']) ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <p class="small">Dokumen dibuat dari sistem EPROC · Dicetak <?= date('d-m-Y H:i') ?></p>
</body>

</html>