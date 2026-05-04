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

    .info-table {
      width: 100%;
      border-collapse: collapse;
    }

    .info-table td {
      padding: 5px 8px;
      border: 1px solid #e5edf5;
      font-size: 8.5pt;
      vertical-align: top;
    }

    .check-section-label {
      font-size: 7pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #004A97;
      margin-bottom: 8px;
      margin-top: 14px;
    }

    .check-line {
      font-size: 8pt;
      color: #2d3748;
      margin-bottom: 4px;
    }
  </style>
</head>

<body>

  <!-- ── Top accent bar ── -->
  <div style="background:#004A97; height:5px; margin-bottom:18px;"></div>

  <!-- ── Header: Logo left · CHECKLIST + Proposal # right ── -->
  <table style="width:100%; border-collapse:collapse; margin-bottom:18px;" cellspacing="0" cellpadding="0">
    <tr>
      <td style="vertical-align:middle;">
        <img src="img/<?= htmlspecialchars($logo) ?>" style="max-width:320px;" alt="Logo">
      </td>
      <td style="vertical-align:bottom; text-align:right;">
        <div style="font-size:28pt; font-weight:700; color:#004A97; letter-spacing:5px; line-height:1; margin-bottom:10px;">CHECKLIST</div>
        <table style="border-collapse:collapse; margin-left:auto;" cellspacing="0" cellpadding="0">
          <tr>
            <td style="padding:5px 14px; background:#004A97; color:#fff; font-size:7pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;">Proposal #</td>
          </tr>
          <tr>
            <td style="padding:6px 14px; border:1px solid #dce8f5; font-size:10pt; font-weight:700; text-align:center; color:#004A97;"><?= htmlspecialchars($quote->obtener_id()) ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <hr style="border:none; border-top:1px solid #d9e4f0; margin:0 0 16px 0;">

  <!-- ── Main two-column body ── -->
  <table style="width:100%; border-collapse:collapse;" cellspacing="0" cellpadding="0">
    <tr>

      <!-- Left: Checkbox groups -->
      <td style="width:28%; vertical-align:top; padding-right:16px;">

        <div class="check-section-label">File Document</div>
        <?php foreach (FILE_DOCUMENT as $key => $file_document) : ?>
          <div class="check-line">
            <?= in_array($key, $quote->getFileDocument()) ? $icons['check'] : $icons['checkbox'] ?>
            <?= htmlspecialchars($file_document) ?>
          </div>
        <?php endforeach; ?>
        <div class="check-line"><?= $icons['checkbox'] ?> ________________</div>
        <div class="check-line"><?= $icons['checkbox'] ?> ________________</div>

        <div class="check-section-label">Accounting</div>
        <?php foreach (ACCOUNTING_CHECKBOX as $key => $accounting) : ?>
          <div class="check-line">
            <?= in_array($key, $quote->getAccounting()) ? $icons['check'] : $icons['checkbox'] ?>
            <?= htmlspecialchars($accounting) ?>
          </div>
        <?php endforeach; ?>
        <div class="check-line"><?= $icons['checkbox'] ?> ________________</div>
        <div class="check-line"><?= $icons['checkbox'] ?> ________________</div>

      </td>

      <!-- Right: Info fields -->
      <td style="vertical-align:top;">
        <?php
          $lbl    = 'width:40%; background:#004A97; color:#fff; font-weight:700; font-size:7.5pt; text-transform:uppercase; letter-spacing:0.4px; white-space:nowrap; padding:5px 8px; border:1px solid #dce8f5; vertical-align:top;';
          $lbl_hl = 'width:40%; background:#003570; color:#fff; font-weight:700; font-size:7.5pt; text-transform:uppercase; letter-spacing:0.4px; white-space:nowrap; padding:5px 8px; border:1px solid #dce8f5; vertical-align:top;';
          $val    = 'background:#fff; padding:5px 8px; border:1px solid #e5edf5; font-size:8.5pt; vertical-align:top;';
          $val_hl = 'background:#fff; padding:5px 8px; border:1px solid #e5edf5; font-size:8.5pt; vertical-align:top; font-weight:700; color:#004A97;';
        ?>
        <table class="info-table" cellspacing="0" cellpadding="0">
          <tr>
            <td style="<?= $lbl ?>">Set Aside</td>
            <td style="<?= $val ?>"><?= htmlspecialchars($quote->getSetSide()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">Channel</td>
            <td style="<?= $val ?>"><?= htmlspecialchars($quote->obtener_canal()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">GSA</td>
            <td style="<?= $val ?>"><?= htmlspecialchars(GSA[$quote->getGsa()] ?? '') ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl_hl ?>">Contract Number</td>
            <td style="<?= $val_hl ?>"><?= htmlspecialchars($quote->obtener_contract_number()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl_hl ?>">BPA</td>
            <td style="<?= $val_hl ?>"><?= $quote->getBpa() ? $icons['check'] : $icons['cross'] ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">Sales Rep</td>
            <td style="<?= $val ?>"><?= htmlspecialchars($usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">RFQ / RFP Number</td>
            <td style="<?= $val ?>"><?= htmlspecialchars($quote->obtener_email_code()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">Award Date</td>
            <td style="<?= $val ?>"><?= htmlspecialchars(date('m/d/Y', strtotime($quote->obtener_fecha_award()))) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">POC</td>
            <td style="<?= $val ?>"><?= htmlspecialchars($quote->getPoc()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">CO</td>
            <td style="<?= $val ?>"><?= htmlspecialchars($quote->getCo()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">Client</td>
            <td style="<?= $val ?>"><?= htmlspecialchars($quote->obtener_client()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl_hl ?>">Contract Amount</td>
            <td style="<?= $val_hl ?>">$ <?= number_format($quote->obtener_quote_total_price(), 2) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl_hl ?>">RFQ Amount</td>
            <td style="<?= $val_hl ?>">$ <?= number_format($quote->obtener_total_price(), 2) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl_hl ?>">RFP Amount</td>
            <td style="<?= $val_hl ?>">$ <?= number_format($quote->getTotalQuoteServices() ?? 0, 2) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">Estimated Delivery Date</td>
            <td style="<?= $val ?>"><?= !empty($quote->getEstimatedDeliveryDate()) ? htmlspecialchars(RepositorioComment::mysql_date_to_english_format($quote->getEstimatedDeliveryDate())) : '' ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">Client Payment Terms</td>
            <td style="<?= $val ?>"><?= htmlspecialchars(CLIENT_PAYMENT_TERMS[$quote->getClientPaymentTerms()] ?? '') ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl_hl ?>">Estimated Profit (RFQ)</td>
            <td style="<?= $val_hl ?>">$ <?= number_format($quote->obtener_re_quote_rfq_profit(), 2) ?> &nbsp;/&nbsp; <?= number_format($quote->obtener_re_quote_rfq_profit_percentage(), 2) ?>%</td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">Shipping Address</td>
            <td style="<?= $val ?>"><?= htmlspecialchars(SHIPPING_ADDRESS[$quote->getShippingAddress()] ?? '') ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">City</td>
            <td style="<?= $val ?>"><?= htmlspecialchars($quote->obtener_city()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">Zip Code</td>
            <td style="<?= $val ?>"><?= htmlspecialchars($quote->obtener_zip_code()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">State</td>
            <td style="<?= $val ?>"><?= htmlspecialchars($quote->obtener_state()) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">Ship To</td>
            <td style="<?= $val ?>"><?= nl2br(htmlspecialchars($quote->obtener_ship_to())) ?></td>
          </tr>
          <tr>
            <td style="<?= $lbl ?>">Special Requirements / Risk / Extra Comments</td>
            <td style="<?= $val ?>"><?= nl2br(htmlspecialchars($quote->getSpecialRequirements() ?? '')) ?></td>
          </tr>
        </table>
      </td>

    </tr>
  </table>

  <!-- ── Signature strip ── -->
  <div style="margin-top:20px; padding:10px 14px; border-left:3px solid #004A97; background:#F7FAFD; font-size:8.5pt; color:#1a2d45;">
    <b style="color:#004A97;">Accepted By:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b style="color:#004A97;">Date — Signature:</b>
  </div>

</body>
</html>
