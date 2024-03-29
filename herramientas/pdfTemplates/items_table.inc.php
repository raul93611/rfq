<!DOCTYPE html>
<html>

<head>
  <style>
    body {
      font-family: roboto;
    }

    th {
      color: #004A97;
      background-color: #DEE8F2;
    }

    .tabla th,
    .tabla td {
      border: 1px solid #DEE8F2;

      padding-left: 10px;
      padding-right: 10px;
      padding-top: 5px;
      padding-bottom: 5px;
      font-size: 7pt;
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
      font-size: 15pt;
    }
  </style>
</head>

<body>
  <table border=0 width="100%">
    <tr>
      <td>
        <table class="tabla" style="width:100%">
          <tr>
            <th>SHIP VIA</th>
            <th>CONTRACT NUMBER</th>
            <th>SALES REP</th>
            <th>E-MAIL</th>
            <th>PAYMENT TERMS</th>
          </tr>
          <tr>
            <td style="text-align:center;"><?= $cotizacion->obtener_ship_via() ?></td>
            <td style="text-align:center;"><?= $cotizacion->obtener_email_code() ?></td>
            <td style="text-align:center;"><?= $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos() ?></td>
            <td style="text-align:center;"><?= $usuario_designado->obtener_email() ?></td>
            <td style="text-align:center;"><?= $cotizacion->obtener_payment_terms() ?></td>
          </tr>
        </table>
      </td>
      <td align="right">
        <table class="tabla">
          <tr>
            <th>PROPOSAL #</th>
            <th>DATE</th>
            <th>EXPIRATION DATE</th>
          </tr>
          <tr>
            <td style="text-align:center;"><?= $cotizacion->obtener_id() ?></td>
            <td style="text-align:center;"><?= $fecha_completado ?></td>
            <td style="text-align:center;"><?= $expiration_date ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <div>
  </div>
  <div class="color">
    <b>Taxes:</b> <?= $cotizacion->obtener_taxes() ?>%<br>
    <b>Profit:</b> <?= $cotizacion->obtener_profit() ?>%<br>
    <b>Additional general: </b> $ <?= $cotizacion->obtener_additional() ?>
  </div>
  <br>
  <?php if (count($items)) : ?>
    <table class="tabla" style="width:100%;">
      <tr>
        <th class="quantity">#</th>
        <th>PROJECT ESPC.</th>
        <th>E-LOGIC PROP.</th>
        <th class="quantity">QTY</th>
        <th>PROVIDER</th>
        <th>ADDITIONAL</th>
        <th>BEST UNIT COST</th>
        <th>TOTAL COST</th>
        <th>PRICE FOR CLIENT</th>
        <th class="total_ancho">TOTAL PRICE</th>
      </tr>
      <?php
      $a = 1;
      if ($cotizacion->obtener_payment_terms() == 'Net 30/CC') {
        $payment_terms = 1.0299;
      } else {
        $payment_terms = 1;
      }
      foreach ($items as $key => $item) {
        echo ProposalRepository::print_item_pdf($cotizacion, $item, $a, $payment_terms);
        $a++;
      }
      ?>
      <tr>
        <td style="border:none;"></td>
        <td colspan="8" style="font-size:10pt;"><?= nl2br($cotizacion->obtener_shipping()) ?></td>
        <td style="text-align:right;">$ <?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
      </tr>
      <tr>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="font-size:12pt;">TOTAL:</td>
        <td>$ <?= number_format($cotizacion->obtener_total_cost(), 2) ?></td>
        <td></td>
        <td style="font-size:12pt;text-align:right;">$ <?= number_format($cotizacion->obtener_total_price(), 2) ?></td>
      </tr>
    </table>
  <?php endif; ?>
  <?php if ($cotizacion->obtener_payment_terms() == 'Net 30') : ?>
    <br>
    <div class="color letra_chiquita"><b>PAYMENT TERMS</b><br><b>NET TERMS: </b>30 Days<br><b>CREDIT CARD PAYMENT: </b>Please add an additional 2.1% to process credit card payments.</div>
  <?php endif; ?>
</body>

</html>