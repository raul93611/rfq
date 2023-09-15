<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>
    body {
      font-family: roboto;
    }

    th {
      color: #004A97;
      background-color: #DEE8F2;
    }

    .blue {
      color: #004A97;
      background-color: #DEE8F2;
    }

    #tabla th,
    #tabla td {
      border: 1px solid #DEE8F2;

      padding-left: 10px;
      padding-right: 10px;
      padding-top: 5px;
      padding-bottom: 5px;
      font-size: 9pt;
    }

    table,
    th,
    td {
      border-collapse: collapse;
    }

    td {
      color: #3B3B3B;
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

    .color {
      color: #004A97;
    }

    .letra_grande {
      font-size: 25pt;
    }
  </style>
</head>

<body>
  <table border=0 width="100%">
    <tr>
      <td width="400">
        <img style="width:350px;height:130px;" src="img/<?= $logo ?>">
      </td>
      <td align="right">
        <span class="color letra_grande"> CHECKLIST</span>
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
  <div>
  </div>
  <br>
  <table id="tabla" style="width:100%">
    <tr>
      <td style="vertical-align:top;">
        <h3>FILE DOCUMENT</h3><br>
        <?php
        foreach (FILE_DOCUMENT as $key => $file_document) {
          echo (in_array($key, $quote->getFileDocument()) ? $check : $checkbox) . ' ' . $file_document . '<br>';
        }
        echo $checkbox . '________________<br>';
        echo $checkbox . '________________<br>';
        ?>
        <br>
        <h3>ACCOUNTING</h3><br>
        <?php
        foreach (ACCOUNTING_CHECKBOX as $key => $accounting) {
          echo (in_array($key, $quote->getAccounting()) ? $check : $checkbox) . ' ' . $accounting . '<br>';
        }
        echo $checkbox . '________________<br>';
        echo $checkbox . '________________<br>';
        ?>
      </td>
      <td style="width:70%;padding: 0;margin: 0;vertical-align:top;">
        <table border=0 width="100%">
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
            <td><?= GSA[$quote->getGsa()] ?></td>
          </tr>
          <tr>
            <td class="blue"><b>Contract Number:</b></td>
            <td><?= $quote->obtener_contract_number() ?></td>
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
            <td>$ <?= number_format($quote->getTotalQuoteServices(), 2) ?></td>
          </tr>
          <tr>
            <td><b>Estimated Delivery Date:</b></td>
            <td><?= (!empty($quote->getEstimatedDeliveryDate()) ? RepositorioComment::mysql_date_to_english_format($quote->getEstimatedDeliveryDate()) : '') ?></td>
          </tr>
          <tr>
            <td><b>Client Payment Terms:</b></td>
            <td><?= CLIENT_PAYMENT_TERMS[$quote->getClientPaymentTerms()] ?></td>
          </tr>
          <tr>
            <td class="blue"><b>Estimated Profit (RFQ):</b></td>
            <td>$ <?= number_format($quote->obtener_re_quote_rfq_profit(), 2) . ' / ' . number_format($quote->obtener_re_quote_rfq_profit_percentage(), 2) . ' %' ?></td>
          </tr>
          <tr>
            <td><b>Shipping Address:</b></td>
            <td><?= SHIPPING_ADDRESS[$quote->getShippingAddress()] ?></td>
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
            <td><?= nl2br($quote->getSpecialRequirements()) ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br><br>
  <b class="blue">ACCEPTED BY:</b>
  <br><br>
  <b class="blue">DATE-SIGNATURE:</b>
</body>

</html>