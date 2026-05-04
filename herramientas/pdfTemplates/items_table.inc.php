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

    /* ── Internal info strip (Taxes / Profit / Additional) ── */
    .info-strip {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
      background: #EEF4FB;
      border: 1px solid #dce8f5;
    }

    .info-strip td {
      padding: 6px 14px;
      font-size: 8pt;
      color: #1a2d45;
      border-right: 1px solid #dce8f5;
      vertical-align: middle;
    }

    .info-strip td:last-child {
      border-right: none;
    }

    .info-strip .i-label {
      font-size: 6.5pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #8fa8c4;
      display: block;
    }

    .info-strip .i-value {
      font-size: 9pt;
      font-weight: 700;
      color: #004A97;
    }

    /* ── Items table ── */
    .items-table {
      width: 100%;
      border-collapse: collapse;
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

    /* ── Payment note ── */
    .payment-note {
      margin-top: 14px;
      padding: 8px 12px;
      border-left: 3px solid #004A97;
      background: #F7FAFD;
      font-size: 7.5pt;
      color: #4a6080;
    }

    .payment-note b {
      color: #004A97;
      display: block;
      margin-bottom: 2px;
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
        <div style="font-size:16pt; font-weight:700; color:#004A97; letter-spacing:3px;">ITEMS TABLE</div>
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
        <div class="d-value"><?= htmlspecialchars($cotizacion->obtener_payment_terms()) ?></div>
      </td>
    </tr>
  </table>

  <!-- ── Internal fields: Taxes / Profit / Additional ── -->
  <table class="info-strip" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <span class="i-label">Taxes</span>
        <span class="i-value"><?= htmlspecialchars($cotizacion->obtener_taxes()) ?>%</span>
      </td>
      <td>
        <span class="i-label">Profit</span>
        <span class="i-value"><?= htmlspecialchars($cotizacion->obtener_profit()) ?>%</span>
      </td>
      <td>
        <span class="i-label">Additional General</span>
        <span class="i-value">$ <?= number_format((float)$cotizacion->obtener_additional(), 2) ?></span>
      </td>
      <td style="width:60%;"></td>
    </tr>
  </table>

  <!-- ── Items table ── -->
  <?php if (count($items)) : ?>
    <?php $payment_terms = ($cotizacion->obtener_payment_terms() == 'Net 30/CC') ? 1.0299 : 1; ?>
    <table class="items-table" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th style="width:18px;">#</th>
          <th class="desc-col">Project Spec.</th>
          <th class="desc-col">E-Logic Prop.</th>
          <th style="width:22px;">Qty</th>
          <th>Provider</th>
          <th>Additional</th>
          <th>Best Unit Cost</th>
          <th>Total Cost</th>
          <th>Price for Client</th>
          <th>Total Price</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $a = 1;
        foreach ($items as $item) {
          echo ProposalRepository::print_item_pdf($cotizacion, $item, $a, $payment_terms);
          $a++;
        }
        ?>
        <tr class="shipping-row">
          <td></td>
          <td colspan="7"><?= nl2br(htmlspecialchars($cotizacion->obtener_shipping())) ?></td>
          <td></td>
          <td style="text-align:right;">$ <?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
        </tr>
        <tr class="total-row">
          <td colspan="6"></td>
          <td style="text-align:right;">TOTAL</td>
          <td style="text-align:right;">$ <?= number_format($cotizacion->obtener_total_cost(), 2) ?></td>
          <td></td>
          <td style="text-align:right;">$ <?= number_format($cotizacion->obtener_total_price(), 2) ?></td>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>

  <?php if ($cotizacion->obtener_payment_terms() == 'Net 30') : ?>
    <div class="payment-note">
      <b>Payment Terms</b>
      Net Terms: 30 Days &nbsp;·&nbsp;
      Credit Card Payment: Please add an additional 2.1% to process credit card payments.
    </div>
  <?php endif; ?>

</body>
</html>
