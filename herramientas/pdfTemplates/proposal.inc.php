<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Roboto, sans-serif;
      font-size: 9pt;
      color: #2d3748;
      line-height: 1.5;
    }

    /* ── Top accent bar ── */
    .top-bar {
      background: #004A97;
      height: 5px;
      margin-bottom: 18px;
    }

    /* ── Thin divider rule ── */
    .rule {
      border: none;
      border-top: 1px solid #d9e4f0;
      margin: 12px 0;
    }

    /* ── Section label ── */
    .section-label {
      font-size: 7pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #004A97;
      margin-bottom: 3px;
    }

    /* ── Address boxes ── */
    .addr-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    .addr-table td {
      width: 50%;
      vertical-align: top;
      padding: 10px 14px;
      background: #F7FAFD;
      border: 1px solid #dce8f5;
      font-size: 9pt;
      color: #2d3748;
    }

    .addr-table td:first-child {
      border-right: none;
    }

    /* ── Details strip ── */
    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 14px;
    }

    .details-table td {
      padding: 8px 12px;
      border: 1px solid #dce8f5;
      vertical-align: top;
      background: #fff;
    }

    .details-table td:not(:first-child) {
      border-left: none;
    }

    .d-label {
      font-size: 7pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: #8fa8c4;
      white-space: nowrap;
    }

    .d-value {
      font-size: 8.5pt;
      font-weight: 700;
      color: #1a2d45;
    }

    /* ── Items table ── */
    .items-table {
      width: 100%;
      border-collapse: collapse;
    }

    .items-table thead th {
      background: #004A97;
      color: #fff;
      font-size: 7.5pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      padding: 9px 10px;
      border: none;
      text-align: center;
    }

    .items-table tbody td {
      padding: 9px 10px;
      border: 1px solid #e5edf5;
      vertical-align: top;
      font-size: 9pt;
      color: #2d3748;
      background: #fff;
    }

    .items-table .sba-row td {
      background: #eef4fb !important;
      font-size: 8pt;
      color: #4a6080;
    }

    .items-table .shipping-row td {
      background: #fafcff !important;
      font-style: italic;
      color: #6b7f96;
      font-size: 8.5pt;
      border-top: 1px solid #c8d8ea;
    }

    .items-table .total-row td {
      background: #F0F5FB !important;
      border-top: 2px solid #004A97 !important;
      border-bottom: none;
      border-left: none;
      border-right: none;
      font-size: 11pt;
      font-weight: 700;
      color: #004A97;
      padding: 10px;
    }

    .num-col   { width: 22px; text-align: center; }
    .qty-col   { width: 30px; text-align: center; }
    .price-col { width: 100px; text-align: right; }
    .total-col { width: 100px; text-align: right; }

    /* ── Payment note ── */
    .payment-note {
      margin-top: 14px;
      padding: 8px 12px;
      border-left: 3px solid #004A97;
      background: #F7FAFD;
      font-size: 8pt;
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
  <div class="top-bar"></div>

  <!-- ── Header: Logo left · PROPOSAL + meta right ── -->
  <table style="width:100%; border-collapse:collapse; margin-bottom:0px;" cellspacing="0" cellpadding="0">
    <tr>
      <td style="vertical-align:top;">
        <img src="img/<?= htmlspecialchars($logo) ?>" style="height:150px; width:auto;" alt="Logo">
      </td>
      <td style="vertical-align:top; text-align:right;">
        <div style="font-size:30pt; font-weight:700; color:#004A97; letter-spacing:5px; line-height:1; margin-bottom:10px;">PROPOSAL</div>
        <table style="border-collapse:collapse; margin-left:auto;" cellspacing="0" cellpadding="0">
          <tr>
            <td style="padding:5px 14px; background:#004A97; color:#fff; font-size:7pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; border-right:1px solid #2d6bbf; text-align:center;">Proposal #</td>
            <td style="padding:5px 14px; background:#004A97; color:#fff; font-size:7pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; border-right:1px solid #2d6bbf; text-align:center;">Date</td>
            <td style="padding:5px 14px; background:#004A97; color:#fff; font-size:7pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; text-align:center;">Expiration</td>
          </tr>
          <tr>
            <td style="padding:6px 14px; border:1px solid #dce8f5; font-size:10pt; font-weight:700; text-align:center; color:#004A97;"><?= htmlspecialchars($cotizacion->obtener_id()) ?></td>
            <td style="padding:6px 14px; border:1px solid #dce8f5; border-left:none; font-size:9pt; text-align:center; color:#2d3748;"><?= htmlspecialchars($fecha_completado) ?></td>
            <td style="padding:6px 14px; border:1px solid #dce8f5; border-left:none; font-size:9pt; text-align:center; color:#2d3748;"><?= htmlspecialchars($expiration_date) ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- ── Address / Ship To ── -->
  <table class="addr-table" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <div class="section-label">Bill To / Address</div>
        <?= nl2br(htmlspecialchars($cotizacion->obtener_address())) ?>
      </td>
      <td>
        <div class="section-label">Ship To</div>
        <?= nl2br(htmlspecialchars($cotizacion->obtener_ship_to())) ?>
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
        <div class="d-value"><?= htmlspecialchars($payment_terms) ?></div>
      </td>
    </tr>
  </table>

  <!-- ── Line Items ── -->
  <table class="items-table" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th class="num-col">#</th>
        <th>Description</th>
        <th class="qty-col">Qty</th>
        <th class="price-col">Unit Price</th>
        <th class="total-col">Total</th>
      </tr>
    </thead>
    <tbody>

      <?php if ($encabezado) : ?>
        <tr class="sba-row">
          <td></td>
          <td colspan="4">
            <b>Open Market Pricing Proposal</b> &nbsp;·&nbsp;
            E-Logic is an SBA 8(a) and HUBZone Certified SB &nbsp;·&nbsp;
            SBA 8(a) Case No: C0069X &nbsp;·&nbsp;
            Entrance: 09/30/2016 &nbsp;·&nbsp; Exit: 09/30/2026
          </td>
        </tr>
      <?php endif; ?>

      <?php
      $a = 1;
      $limit = 400;

      if (!empty($items)) {
        foreach ($items as $item) {
          echo ProposalRepository::print_item($item, $limit, $a)[0];
          $a++;
        }
      }

      if (isset($services)) {
        foreach ($services as $service) {
          echo ProposalRepository::print_service($cotizacion->obtener_services_payment_term(), $service, $a);
          $a++;
        }
      }
      ?>

      <tr class="shipping-row">
        <td></td>
        <td colspan="3"><?= nl2br(htmlspecialchars($cotizacion->obtener_shipping())) ?></td>
        <td class="total-col">$ <?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
      </tr>

      <tr class="total-row">
        <td colspan="3" style="text-align:right;">TOTAL</td>
        <td colspan="2" style="text-align:right;">$ <?= number_format($cotizacion->obtener_total_price() + $total_service, 2) ?></td>
      </tr>

    </tbody>
  </table>

  <?php if ($payment_terms === 'Net 30') : ?>
    <div class="payment-note">
      <b>Payment Terms</b>
      Net Terms: 30 Days &nbsp;·&nbsp;
      Credit Card Payment: Please add an additional 3% to process credit card payments.
    </div>
  <?php endif; ?>

</body>
</html>
