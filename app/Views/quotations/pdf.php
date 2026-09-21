<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 28px 34px
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        td,
        th {
            padding: 4px 5px;
            vertical-align: top
        }

        .header {
            text-align: center
        }

        .logo {
            max-width: 90px;
            max-height: 60px;
            float: left
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px
        }

        .meta {
            margin-top: 10px
        }

        .meta td {
            padding: 1px 3px
        }

        .items {
            margin-top: 14px;
            border: 1px solid #555
        }

        .items th,
        .items td {
            border: 1px solid #777
        }

        .items th {
            background: #eee;
            text-align: center
        }

        .items .num {
            text-align: center
        }

        .items .money {
            text-align: right;
            white-space: nowrap
        }

        .items .description {
            font-size: 8px;
            color: #555
        }

        .totals {
            width: 50%;
            margin-left: 50%;
            margin-top: 4px
        }

        .totals td {
            border-bottom: 1px solid #aaa
        }

        .right {
            text-align: right
        }

        .terms {
            margin-top: 14px
        }

        .sign {
            width: 220px;
            margin-left: auto;
            text-align: center;
            margin-top: 18px
        }

        .sign img {
            max-width: 95px;
            max-height: 65px
        }

        .stamp {
            max-width: 70px !important;
            max-height: 70px !important;
            margin-left: -30px
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td class="header" colspan="2"><?php if ($logoData): ?><img class="logo" src="<?= $logoData ?>"><?php endif; ?><div class="title"><?= esc($settings['company_name'] ?? '') ?></div>
                <div><?= esc($settings['office_1'] ?? '') ?></div>
                <div><?= esc($settings['office_2'] ?? '') ?></div>
                <div><?= esc($settings['phone'] ?? '') ?><?= $settings['email'] ? ', e-mail: ' . esc($settings['email']) : '' ?></div>
                <div><?= $settings['tax_id'] ? 'NPWP : ' . esc($settings['tax_id']) : '' ?></div>
                <div class="title" style="margin-top:10px">QUOTATION</div>
            </td>
        </tr>
    </table>
    <table class="meta">
        <tr>
            <td width="48%"><strong>No</strong> &nbsp;: <?= esc($quotation['quotation_no']) ?></td>
            <td class="right"><?= esc($quotation['issue_date'] ?? '') ?></td>
        </tr>
        <tr>
            <td><strong>To</strong> &nbsp;: <?= esc($quotation['customer_name'] ?? '-') ?><br><span style="padding-left:25px"><?= nl2br(esc($quotation['customer_address'] ?? '')) ?></span></td>
            <td></td>
        </tr>
        <tr>
            <td><strong>About</strong> &nbsp;: <?= esc($quotation['title']) ?></td>
            <td><strong>attn</strong> &nbsp;: <?= esc($quotation['attention'] ?? '-') ?></td>
        </tr>
    </table>
    <table class="items">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">BRAND</th>
                <th>DESCRIPTION</th>
                <th width="10%">QUANTITY</th>
                <th width="8%">UoM</th>
                <th width="15%">PRICE<br>( Rp )</th>
                <th width="16%">AMOUNT<br>( Rp )</th>
            </tr>
        </thead>
        <tbody><?php foreach ($quotation['items'] as $i => $item): ?><tr>
                    <td class="num"><?= $i + 1 ?></td>
                    <td><?= esc($item['product_name']) ?></td>
                    <td><?= esc($item['description'] ?? '') ?></td>
                    <td class="num"><?= esc($item['quantity']) ?></td>
                    <td class="num"><?= esc($item['unit']) ?></td>
                    <td class="money"><?= number_format((float)$item['unit_price'], 0, ',', '.') ?></td>
                    <td class="money"><?= number_format((float)$item['line_total'], 0, ',', '.') ?></td>
                </tr><?php endforeach; ?></tbody>
    </table>
    <table class="totals">
        <tr>
            <td class="right">TOTAL</td>
            <td class="money"><?= number_format((float)$quotation['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td class="right">PPN (<?= esc($quotation['tax_percent']) ?>%)</td>
            <td class="money"><?= number_format((float)$quotation['tax_amount'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <th class="right">Grand Total</th>
            <th class="money"><?= number_format((float)$quotation['grand_total'], 0, ',', '.') ?></th>
        </tr>
    </table>
    <table class="terms">
        <tr>
            <td width="55%"><strong>Quotation Terms and Conditions:</strong><br>1 &nbsp; Price &nbsp; : In IDR, include TAX <?= esc($quotation['tax_percent']) ?>%<br>2 &nbsp; Terms of payment &nbsp;: <?= esc($quotation['payment_terms'] ?? '-') ?><br>3 &nbsp; Quotation Validity &nbsp;: <?= esc($quotation['validity_days'] ?? '-') ?> days<br>4 &nbsp; Delivery &nbsp;: <?= esc($quotation['delivery_terms'] ?? '-') ?></td>
            <td class="sign">
                <div>Sincerely yours,</div><?php if ($signatureData): ?><img src="<?= $signatureData ?>"><?php endif; ?><?php if ($stampData): ?><img class="stamp" src="<?= $stampData ?>"><?php endif; ?><br><strong><?= esc($settings['signer_name'] ?? '') ?></strong><br>( <?= esc($settings['signer_phone'] ?? '') ?> )
            </td>
        </tr>
    </table>
</body>

</html>