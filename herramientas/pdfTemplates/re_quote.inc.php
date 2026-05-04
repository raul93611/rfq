<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: Roboto, sans-serif;
      font-size: 9pt;
      color: #2d3748;
      line-height: 1.4;
    }

    /* ── Details strip ── */
    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    .details-table td {
      padding: 7px 12px;
      border: 1px solid #dce8f5;
      border-left: none;
      vertical-align: top;
      background: #fff;
    }

    .details-table td:first-child {
      border-left: 1px solid #dce8f5;
    }

    .d-label {
      font-size: 6.5pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: #8fa8c4;
      white-space: nowrap;
    }

    .d-value {
      font-size: 8pt;
      font-weight: 700;
      color: #1a2d45;
    }

    /* ── Items table ── */
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 16px;
    }

    .items-table thead th {
      background: #004A97;
      color: #fff;
      font-size: 7pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      padding: 8px 6px;
      border: none;
      text-align: center;
    }

    .items-table thead th.desc-col {
      text-align: left;
    }

    .items-table tbody td {
      padding: 7px 6px;
      border: 1px solid #e5edf5;
      vertical-align: top;
      font-size: 7.5pt;
      color: #2d3748;
      background: #fff;
    }

    .items-table .shipping-row td {
      background: #fafcff;
      font-style: italic;
      color: #6b7f96;
      border-top: 1px solid #c8d8ea;
    }

    .items-table .total-row td {
      background: #F0F5FB !important;
      border-top: 2px solid #004A97 !important;
      border-bottom: none;
      border-left: none;
      border-right: none;
      font-size: 9pt;
      font-weight: 700;
      color: #004A97;
      padding: 8px 6px;
    }

    .items-table .profit-row td {
      background: #F0F5FB !important;
      border-top: none !important;
      border-bottom: none;
      border-left: none;
      border-right: none;
      font-size: 8.5pt;
      font-weight: 700;
      color: #004A97;
      padding: 4px 6px;
    }

    /* ── Section title ── */
    .section-title {
      font-size: 7pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #004A97;
      margin-bottom: 6px;
      margin-top: 14px;
    }
  </style>
</head>

<body>

  <!-- ── Top accent bar ── -->
  <div style="background:#004A97; height:5px; margin-bottom:14px;"></div>

  <!-- ── Page title ── -->
  <table style="width:100%; border-collapse:collapse; margin-bottom:12px;" cellspacing="0" cellpadding="0">
    <tr>
      <td style="vertical-align:middle;">
        <div style="font-size:16pt; font-weight:700; color:#004A97; letter-spacing:3px;">RE-QUOTE</div>
        <div style="font-size:8pt; color:#8fa8c4; margin-top:2px; text-transform:uppercase; letter-spacing:0.5px;">Internal Pricing Reference</div>
      </td>
      <td style="vertical-align:middle; text-align:right;">
        <table style="border-collapse:collapse; margin-left:auto;" cellspacing="0" cellpadding="0">
          <tr>
            <td style="padding:4px 12px; background:#004A97; color:#fff; font-size:6.5pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; border-right:1px solid #2d6bbf;">Proposal #</td>
            <td style="padding:4px 12px; background:#004A97; color:#fff; font-size:6.5pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; border-right:1px solid #2d6bbf;">Date</td>
            <td style="padding:4px 12px; background:#004A97; color:#fff; font-size:6.5pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;">Expiration</td>
          </tr>
          <tr>
            <td style="padding:5px 12px; border:1px solid #dce8f5; font-size:9pt; font-weight:700; text-align:center; color:#004A97;"><?= htmlspecialchars($cotizacion->obtener_id()) ?></td>
            <td style="padding:5px 12px; border:1px solid #dce8f5; border-left:none; font-size:8pt; text-align:center; color:#2d3748;"><?= htmlspecialchars($fecha_completado) ?></td>
            <td style="padding:5px 12px; border:1px solid #dce8f5; border-left:none; font-size:8pt; text-align:center; color:#2d3748;"><?= htmlspecialchars($expiration_date) ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- ── Details strip ── -->
  <table class="details-table" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <div class="d-label">Ship Via</div>
        <div class="d-value"><?= htmlspecialchars($cotizacion->obtener_ship_via()) ?></div>
      </td>
      <td>
        <div class="d-label">Contract Number</div>
        <div class="d-value"><?= htmlspecialchars($cotizacion->obtener_email_code()) ?></div>
      </td>
      <td>
        <div class="d-label">Sales Rep</div>
        <div class="d-value"><?= htmlspecialchars($usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos()) ?></div>
      </td>
      <td>
        <div class="d-label">E-Mail</div>
        <div class="d-value"><?= htmlspecialchars($usuario_designado->obtener_email()) ?></div>
      </td>
      <td>
        <div class="d-label">Payment Terms</div>
        <div class="d-value"><?= htmlspecialchars($re_quote->get_payment_terms()) ?></div>
      </td>
    </tr>
  </table>

  <!-- ── Re-Quote Items ── -->
  <?php if (!empty($re_quote_items)) : ?>
    <div class="section-title">Items</div>
    <table class="items-table" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th style="width:18px;">#</th>
          <th class="desc-col">Project Spec.</th>
          <th class="desc-col">E-Logic Prop.</th>
          <th style="width:22px;">Qty</th>
          <th>Provider</th>
          <th>Best Unit Cost</th>
          <th>Total Cost</th>
          <th>Price for Client</th>
          <th>Total Price</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($re_quote_items as $key => $re_quote_item) : ?>
          <?= ProposalRepository::print_item_pdf_re_quote($re_quote_item, $items, $key + 1, $key) ?>
        <?php endforeach; ?>
        <tr class="shipping-row">
          <td></td>
          <td colspan="5"><?= nl2br(htmlspecialchars($re_quote->get_shipping())) ?></td>
          <td style="text-align:right;">$ <?= number_format($re_quote->get_shipping_cost(), 2) ?></td>
          <td></td>
          <td style="text-align:right;">$ <?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
        </tr>
        <tr class="total-row">
          <td colspan="5"></td>
          <td style="text-align:right;">TOTAL</td>
          <td style="text-align:right;">$ <?= number_format($re_quote->get_total_cost(), 2) ?></td>
          <td></td>
          <td style="text-align:right;">$ <?= number_format($cotizacion->obtener_total_price(), 2) ?></td>
        </tr>
        <?php
          $profit     = $cotizacion->obtener_total_price() - $re_quote->get_total_cost();
          $profit_pct = $cotizacion->obtener_total_price() > 0
            ? ($profit / $cotizacion->obtener_total_price() * 100)
            : 0;
        ?>
        <tr class="profit-row">
          <td colspan="7"></td>
          <td style="text-align:right;">PROFIT</td>
          <td style="text-align:right;">$ <?= number_format($profit, 2) ?></td>
        </tr>
        <tr class="profit-row">
          <td colspan="8"></td>
          <td style="text-align:right;"><?= number_format($profit_pct, 2) ?>%</td>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>

  <!-- ── Re-Quote Services ── -->
  <?php if (!empty($re_quote_services)) : ?>
    <div class="section-title">Services</div>
    <table class="items-table" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th style="width:18px;">#</th>
          <th class="desc-col">Description</th>
          <th style="width:30px;">Qty</th>
          <th style="width:90px;">Unit Price</th>
          <th style="width:100px;">Total Price</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($re_quote_services as $key => $re_quote_service) : ?>
          <?= ProposalRepository::print_service_pdf_re_quote($re_quote->get_services_payment_term(), $cotizacion->obtener_services_payment_term(), $re_quote_service, $key + 1) ?>
        <?php endforeach; ?>
        <tr class="total-row">
          <td colspan="4" style="text-align:right;">TOTAL</td>
          <td style="text-align:right;">$ <?= number_format($total_services, 2) ?></td>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>

</body>
</html>
