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

    .rule {
      border: none;
      border-top: 1px solid #d9e4f0;
      margin: 12px 0;
    }

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
      border-left: none;
      vertical-align: top;
      background: #fff;
    }

    .details-table td:first-child {
      border-left: 1px solid #dce8f5;
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

    /* ── Items / room table ── */
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 18px;
    }

    /* Room name header row */
    .items-table .room-header td {
      background: #EEF4FB;
      color: #004A97;
      font-size: 9.5pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 8px 10px;
      border: 1px solid #dce8f5;
    }

    /* Column headers */
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

    /* Per-room total row */
    .items-table .room-total td {
      background: #F0F5FB !important;
      border-top: 2px solid #004A97 !important;
      border-bottom: none;
      border-left: none;
      border-right: none;
      font-size: 10pt;
      font-weight: 700;
      color: #004A97;
      padding: 8px 10px;
    }

    .num-col {
      width: 22px;
      text-align: center;
    }

    .qty-col {
      width: 30px;
      text-align: center;
    }

    .price-col {
      width: 100px;
      text-align: right;
    }

    .total-col {
      width: 100px;
      text-align: right;
    }

    /* ── Grand total table ── */
    .grand-total-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 4px;
    }

    .grand-total-table .shipping-row td {
      padding: 8px 10px;
      background: #fafcff;
      font-style: italic;
      color: #6b7f96;
      font-size: 8.5pt;
      border: 1px solid #e5edf5;
    }

    .grand-total-table .total-row td {
      background: #F0F5FB;
      border-top: 2px solid #004A97;
      border-bottom: none;
      border-left: none;
      border-right: none;
      font-size: 11pt;
      font-weight: 700;
      color: #004A97;
      padding: 10px;
    }

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
  <div style="background:#004A97; height:5px; margin-bottom:18px;"></div>

  <!-- ── Header: Logo left · PROPOSAL + meta right ── -->
  <table style="width:100%; border-collapse:collapse; margin-bottom:0px;" cellspacing="0" cellpadding="0">
    <tr>
      <td style="vertical-align:top;">
        <img src="img/<?= htmlspecialchars($logo) ?>" style="height:190px; width:auto;" alt="Logo">
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

  <hr class="rule">

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

  <!-- ── Rooms ── -->
  <?php foreach ($rooms as $room) : ?>
    <table class="items-table" cellspacing="0" cellpadding="0">
      <tbody>
        <tr class="room-header">
          <td colspan="5"><?= htmlspecialchars(strtoupper($room->getName())) ?></td>
        </tr>
      </tbody>
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
        <?php
        $a = 1;
        $total_room = 0;
        if (count($items[$room->getName()])) {
          foreach ($items[$room->getName()] as $item) {
            list($html, $subitems_total) = ProposalRepository::print_item($item, 400, $a);
            echo $html;
            $total_room += $subitems_total;
            $total_room += $item->obtener_total_price();
            $a++;
          }
        }
        if (isset($services[$room->getName()])) {
          foreach ($services[$room->getName()] as $service) {
            echo ProposalRepository::print_service($cotizacion->obtener_services_payment_term(), $service, $a);
            $total_room += $service->get_total_price();
            $a++;
          }
        }
        ?>
        <tr class="room-total">
          <td colspan="4" style="text-align:right;">ROOM TOTAL</td>
          <td class="total-col">$ <?= number_format($total_room, 2) ?></td>
        </tr>
      </tbody>
    </table>
  <?php endforeach; ?>

  <!-- ── Shipping + Grand Total ── -->
  <table class="grand-total-table" cellspacing="0" cellpadding="0">
    <tr class="shipping-row">
      <td><?= nl2br(htmlspecialchars($cotizacion->obtener_shipping())) ?></td>
      <td style="text-align:right; width:150px;">$ <?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
    </tr>
    <tr class="total-row">
      <td style="text-align:right;">TOTAL</td>
      <td style="text-align:right; width:150px;">$ <?= number_format($cotizacion->obtener_total_price() + $total_service, 2) ?></td>
    </tr>
  </table>

  <?php if ($payment_terms == 'Net 30') : ?>
    <div class="payment-note">
      <b>Payment Terms</b>
      Net Terms: 30 Days &nbsp;·&nbsp;
      Credit Card Payment: Please add an additional 3% to process credit card payments.
    </div>
  <?php endif; ?>

</body>

</html>