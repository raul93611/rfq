<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 8px 10px;
      border: 1px solid #DEE8F2;
      font-size: 9pt;
      text-align: left;
    }

    th {
      background-color: #DEE8F2;
      color: #004A97;
    }

    .blue {
      color: #004A97;
      font-weight: bold;
    }

    .letra_grande {
      font-size: 25pt;
    }

    .color {
      color: #004A97;
    }

    .quantity {
      width: 20px;
    }

    .total_ancho {
      width: 130px;
    }

    .letra_chiquita {
      font-size: 8pt;
    }

    .checkbox-group {
      margin-top: 20px;
    }

    .checkbox-group h3 {
      margin-bottom: 10px;
    }

    .checkbox-group label {
      display: block;
    }

    .checkbox-group label span {
      display: inline-block;
      width: 15px;
      height: 15px;
      border: 1px solid #DEE8F2;
      margin-right: 5px;
      vertical-align: middle;
    }

    .section-header {
      margin-top: 40px;
      margin-bottom: 10px;
    }

    .section-table td {
      padding: 5px 10px;
    }
  </style>
</head>

<body>
  <table>
    <tr>
      <td width="400">
        <img src="img/<?= $logo ?>" alt="Logo" style="width:350px;height:130px;" />
      </td>
      <td align="right">
        <span class="color letra_grande">CHECKLIST</span>
        <br><br>
        <table id="tabla">
          <tr>
            <th>PROPOSAL #</th>
          </tr>
          <tr>
            <td style="text-align:center;"><?= $quote->obtener_id() ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <table id="tabla" style="width:100%">
    <tr>
      <td style="vertical-align:top;">
        <div class="checkbox-group">
          <h3>FILE DOCUMENT</h3>
          <br>
          <?php
          foreach (FILE_DOCUMENT as $key => $file_document) {
            echo (in_array($key, $quote->getFileDocument()) ? $icons['check'] : $icons['checkbox']) . ' ' . $file_document . '<br>';
          }
          echo $icons['checkbox'] . '________________<br>';
          echo $icons['checkbox'] . '________________<br>';
          ?>
          <br>
          <h3>ACCOUNTING</h3>
          <br>
          <?php
          foreach (ACCOUNTING_CHECKBOX as $key => $accounting) {
            echo (in_array($key, $quote->getAccounting()) ? $icons['check'] : $icons['checkbox']) . ' ' . $accounting . '<br>';
          }
          echo $icons['checkbox'] . '________________<br>';
          echo $icons['checkbox'] . '________________<br>';
          ?>
        </div>
      </td>
      <td style="width:70%;padding: 0;margin: 0;vertical-align:top;">
        <div class="section-header">
          <table class="section-table">
            <tr>
              <td><b>Set Aside:</b></td>
              <td><?= $quote->getSetSide() ?></td>
            </tr>
            <tr>
              <td><b>Channel:</b></td>
              <td><?= $quote->obtener_canal() ?></td>
            </tr>
            <tr>
              <td><b>GSA:</b></td>
              <td><?= GSA[$quote->getGsa()] ?? '' ?></td>
            </tr>
            <tr>
              <td class="blue"><b>Contract Number:</b></td>
              <td><?= $quote->obtener_contract_number() ?></td>
            </tr>
            <tr>
              <td class="blue"><b>BPA:</b></td>
              <td><?= $quote->getBpa() ? $icons['check'] : $icons['cross'] ?></td>
            </tr>
            <tr>
              <td><b>Sales Rep:</b></td>
              <td><?= $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos() ?></td>
            </tr>
            <tr>
              <td><b>RFQ/RFP Number:</b></td>
              <td><?= $quote->obtener_email_code() ?></td>
            </tr>
            <tr>
              <td><b>Award Date:</b></td>
              <td><?= date('m/d/Y', strtotime($quote->obtener_fecha_award())) ?></td>
            </tr>
            <tr>
              <td><b>POC:</b></td>
              <td><?= $quote->getPoc() ?></td>
            </tr>
            <tr>
              <td><b>CO:</b></td>
              <td><?= $quote->getCo() ?></td>
            </tr>
            <tr>
              <td><b>Client:</b></td>
              <td><?= $quote->obtener_client() ?></td>
            </tr>
            <tr>
              <td class="blue"><b>Contract Amount:</b></td>
              <td>$ <?= number_format($quote->obtener_quote_total_price(), 2) ?></td>
            </tr>
            <tr>
              <td class="blue"><b>RFQ Amount:</b></td>
              <td>$ <?= number_format($quote->obtener_total_price(), 2) ?></td>
            </tr>
            <tr>
              <td class="blue"><b>RFP Amount:</b></td>
              <td>$ <?= number_format($quote->getTotalQuoteServices() ?? 0, 2) ?></td>
            </tr>
            <tr>
              <td><b>Estimated Delivery Date:</b></td>
              <td><?= (!empty($quote->getEstimatedDeliveryDate()) ? RepositorioComment::mysql_date_to_english_format($quote->getEstimatedDeliveryDate()) : '') ?></td>
            </tr>
            <tr>
              <td><b>Client Payment Terms:</b></td>
              <td><?= CLIENT_PAYMENT_TERMS[$quote->getClientPaymentTerms()] ?? '' ?></td>
            </tr>
            <tr>
              <td class="blue"><b>Estimated Profit (RFQ):</b></td>
              <td>$ <?= number_format($quote->obtener_re_quote_rfq_profit(), 2) . ' / ' . number_format($quote->obtener_re_quote_rfq_profit_percentage(), 2) . ' %' ?></td>
            </tr>
            <tr>
              <td><b>Shipping Address:</b></td>
              <td><?= SHIPPING_ADDRESS[$quote->getShippingAddress()] ?? '' ?></td>
            </tr>
            <tr>
              <td><b>City:</b></td>
              <td><?= $quote->obtener_city() ?></td>
            </tr>
            <tr>
              <td><b>Zip Code:</b></td>
              <td><?= $quote->obtener_zip_code() ?></td>
            </tr>
            <tr>
              <td><b>State:</b></td>
              <td><?= $quote->obtener_state() ?></td>
            </tr>
            <tr>
              <td><b>Ship to:</b></td>
              <td><?= nl2br($quote->obtener_ship_to()) ?></td>
            </tr>
            <tr>
              <td><b>Special Requirements/Risk/Extra Comments:</b></td>
              <td><?= nl2br($quote->getSpecialRequirements() ?? '') ?></td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>
  <br><br>
  <div>
    <b class="blue">ACCEPTED BY:</b><br><br>
    <b class="blue">DATE-SIGNATURE:</b>
  </div>
</body>

</html>