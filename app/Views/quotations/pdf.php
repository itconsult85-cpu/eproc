<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
    @page {
        margin: 30px 40px;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: #000;
        line-height: 1.3;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    /* Kop Surat */
    .header-title {
        font-family: "Times New Roman", Times, serif;
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 2px;
    }

    .header-info {
        font-size: 10px;
        line-height: 1.2;
        margin: 0;
        padding: 0;
    }

    /* Tabel Meta */
    .meta-table td {
        padding: 3px 0;
        vertical-align: top;
    }

    /* Tabel Item */
    .items-table {
        margin-bottom: 10px;
    }

    .items-table th,
    .items-table td {
        border: 1px solid #000;
        padding: 8px 5px;
    }

    .items-table th {
        text-align: center;
        font-weight: bold;
        background-color: #d9d9d9;
        font-size: 10px;
    }

    /* Utility */
    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-blue {
        color: #2a5788;
    }

    .text-grey {
        color: #555555;
    }

    .no-border {
        border: none !important;
    }
    </style>
</head>

<body>
    <?php
    // Format Tanggal
    $bulan = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
    $tgl = explode('-', $quotation['issue_date']);
    $formatTanggal = "Jakarta, " . (int)$tgl[2] . " " . $bulan[$tgl[1]] . " " . $tgl[0];

    // Ambil Gambar Produk dari DB
    $db = \Config\Database::connect();
    foreach ($quotation['items'] as $k => $v) {
        $quotation['items'][$k]['img_base64'] = '';
        if (!empty($v['product_id'])) {
            $prod = $db->table('products')->select('image_path')->where('id', $v['product_id'])->get()->getRow();
            if ($prod && $prod->image_path && is_file(FCPATH . ltrim($prod->image_path, '/'))) {
                $mime = mime_content_type(FCPATH . ltrim($prod->image_path, '/'));
                $quotation['items'][$k]['img_base64'] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents(FCPATH . ltrim($prod->image_path, '/')));
            }
        }
    }
    ?>

    <!-- 1. KOP SURAT (Garis Ganda diletakkan sebagai border-bottom dari sel tabel agar menempel pada teks) -->
    <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
        <tr>
            <td style="width: 25%; vertical-align: bottom; border-bottom: 3px double #000; padding: 0 0 2px 0;">
                <?php if ($logoData): ?>
                <img src="<?= $logoData ?>" style="width: 140px; margin: 0; display: block;">
                <?php endif; ?>
            </td>
            <td
                style="width: 75%; vertical-align: bottom; text-align: center; border-bottom: 3px double #000; padding: 0 0 2px 0;">
                <div class="header-title">
                    <?= esc(strtoupper($settings['company_name'] ?? 'PT. TRISENTOSA RAYA ESOLUSI')) ?>
                </div>
                <div class="header-info">
                    Office 1 : <?= esc($settings['office_1'] ?? '') ?><br>
                    Office 2 : <?= esc($settings['office_2'] ?? '') ?><br>
                    Telp:
                    <?= esc($settings['phone'] ?? '') ?><?= $settings['email'] ? ', e-mail: ' . esc($settings['email']) : '' ?><br>
                    <?= $settings['tax_id'] ? 'NPWP : ' . esc($settings['tax_id']) : '' ?>
                </div>
            </td>
        </tr>
    </table>

    <!-- 2. META DATA & KOTAK QUOTATION (Margin atas dinolkan agar menempel dengan tabel di atas) -->
    <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0; margin-bottom: 15px;">
        <tr>
            <!-- Sisi Kiri: Informasi Customer -->
            <td style="width: 65%; vertical-align: top; padding-top: 15px;">
                <table class="meta-table">
                    <tr>
                        <td width="12%">No</td>
                        <td width="3%">:</td>
                        <td><?= esc($quotation['quotation_no']) ?></td>
                    </tr>
                    <tr>
                        <td>To</td>
                        <td>:</td>
                        <td>
                            <strong><?= esc($quotation['customer_name'] ?: ($quotation['company_name'] ?? '-')) ?></strong><br>
                            <?= nl2br(esc($quotation['customer_address'] ?: ($quotation['company_address'] ?? ''))) ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;">About</td>
                        <td style="padding-top:10px;">:</td>
                        <td style="padding-top:10px;"><strong><?= esc($quotation['title']) ?></strong></td>
                    </tr>
                    <tr>
                        <td>Attn</td>
                        <td>:</td>
                        <td class="text-grey"><?= esc($quotation['attention'] ?? '-') ?></td>
                    </tr>
                </table>
            </td>
            <!-- Sisi Kanan: Kotak QUOTATION rata kanan dan menempel dengan border ganda -->
            <td style="width: 35%; vertical-align: top; padding: 0;">
                <table style="width: 220px; float: right; border-collapse: collapse; margin: 0;">
                    <tr>
                        <td
                            style="border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: none; text-align: center; font-weight: bold; font-size: 13px; padding: 6px;">
                            QUOTATION
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding-top: 5px; font-size: 11px; border: none;">
                            <?= $formatTanggal ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- 3 & 4. TABEL ITEM DENGAN 8 KOLOM -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">IMAGE</th>
                <th width="14%">BRAND</th>
                <th width="28%">DESCRIPTION</th>
                <th width="8%">QUANTITY</th>
                <th width="6%">UoM</th>
                <th width="14%">PRICE<br>( Rp )</th>
                <th width="14%">AMOUNT<br>( Rp )</th>
            </tr>
            <!-- Jeda Baris Tanpa Border -->
            <tr style="height: 12px;">
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quotation['items'] as $i => $item): ?>
            <tr>
                <td class="text-center" style="vertical-align: middle;"><?= $i + 1 ?></td>
                <td class="text-center" style="vertical-align: middle;">
                    <?php if (!empty($item['img_base64'])): ?>
                    <img src="<?= $item['img_base64'] ?>" style="max-width: 65px; max-height: 75px;">
                    <?php endif; ?>
                </td>
                <td class="text-center" style="vertical-align: top; padding-top: 15px; font-weight: bold;">
                    <span class="text-blue"><?= esc($item['product_name']) ?></span>
                </td>
                <td style="vertical-align: top; padding-top: 15px;">
                    <strong><?= nl2br(esc(strtoupper($item['description'] ?? ''))) ?></strong>
                </td>
                <td class="text-center" style="vertical-align: top; padding-top: 15px;"><?= esc($item['quantity']) ?>
                </td>
                <td class="text-center" style="vertical-align: top; padding-top: 15px;"><?= esc($item['unit']) ?></td>
                <td class="text-right" style="vertical-align: top; padding-top: 15px;">
                    <?= number_format((float)$item['unit_price'], 0, ',', '.') ?></td>
                <td class="text-right" style="vertical-align: top; padding-top: 15px;">
                    <?= number_format((float)$item['line_total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>

            <!-- Kalkulasi Total -->
            <tr>
                <td colspan="6" class="no-border"></td>
                <td class="text-center" style="border: 1px solid #000; font-weight: bold;">TOTAL</td>
                <td class="text-right" style="border: 1px solid #000; font-weight: bold;">
                    <?= number_format((float)$quotation['subtotal'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td colspan="6" class="no-border"></td>
                <td class="text-center" style="border: 1px solid #000; font-weight: bold;">PPN</td>
                <td class="text-right" style="border: 1px solid #000; font-weight: bold;">
                    <?= number_format((float)$quotation['tax_amount'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td colspan="6" class="no-border"></td>
                <td class="text-center" style="border: 1px solid #000; font-weight: bold;">Grand Total</td>
                <td class="text-right" style="border: 1px solid #000; font-weight: bold;">
                    <?= number_format((float)$quotation['grand_total'], 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <!-- FOOTER: TERMS & TANDA TANGAN -->
    <table style="margin-top: 15px; border: none;">
        <tr>
            <td width="65%" style="vertical-align: top;">
                <u><strong>Quotation Terms and Conditions:</strong></u>
                <table style="margin-top: 5px; font-size: 10px;">
                    <tr>
                        <td width="4%"><i>1</i></td>
                        <td width="28%"><i>Price</i></td>
                        <td><i>: In IDR, include TAX <?= esc($quotation['tax_percent']) ?>%,</i></td>
                    </tr>
                    <tr>
                        <td><i>2</i></td>
                        <td><i>Terms of payment</i></td>
                        <td><i>: <?= esc($quotation['payment_terms'] ?? '-') ?></i></td>
                    </tr>
                    <tr>
                        <td><i>3</i></td>
                        <td><i>Quotation Validity</i></td>
                        <td><i>: <?= esc($quotation['validity_days'] ?? '-') ?> days</i></td>
                    </tr>
                    <tr>
                        <td><i>4</i></td>
                        <td><i>Delivery</i></td>
                        <td><i>: <?= esc($quotation['delivery_terms'] ?? '-') ?></i></td>
                    </tr>
                </table>
            </td>
            <td width="35%" style="vertical-align: top; text-align: center;">
                Sincerely yours,<br>
                <?php if ($signatureData || $stampData): ?>
                <div style="position: relative; height: 60px; margin: 10px 0;">
                    <?php if ($stampData): ?>
                    <img src="<?= $stampData ?>"
                        style="max-height: 60px; position: absolute; left: 15%; top: 0; z-index: 1; opacity: 0.8;">
                    <?php endif; ?>
                    <?php if ($signatureData): ?>
                    <img src="<?= $signatureData ?>"
                        style="max-height: 50px; position: relative; z-index: 2; margin-top: 5px;">
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <br><br><br><br>
                <?php endif; ?>
                <strong><?= esc($settings['signer_name'] ?? '') ?></strong><br>
                ( <?= esc($settings['signer_phone'] ?? '') ?> )
            </td>
        </tr>
    </table>
</body>

</html>
