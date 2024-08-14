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
  <h2>RE QUOTE</h2>
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
            <td style="text-align:center;"><?= $re_quote->get_payment_terms() ?></td>
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
  <br>
  <?php if (count($re_quote_items)) : ?>
    <table class="tabla" style="width:100%;">
      <tr>
        <th class="quantity">#</th>
        <th>PROJECT ESPC.</th>
        <th>E-LOGIC PROP.</th>
        <th class="quantity">QTY</th>
        <th>PROVIDER</th>
        <th>BEST UNIT COST</th>
        <th>TOTAL COST</th>
        <th>PRICE FOR CLIENT</th>
        <th>TOTAL PRICE</th>
      </tr>
      <?php
      $a = 1;
      foreach ($re_quote_items as $key => $re_quote_item) {
        echo ProposalRepository::print_item_pdf_re_quote($re_quote_item, $items, $a, $key);
        $a++;
      }
      ?>
      <tr>
        <td style="border:none;"></td>
        <td colspan="5" style="font-size:10pt;"><?= nl2br($re_quote->get_shipping()) ?></td>
        <td style="text-align:right;">$ <?= number_format($re_quote->get_shipping_cost(), 2) ?></td>
        <td></td>
        <td>$ <?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
      </tr>
      <tr>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="font-size:12pt;">TOTAL:</td>
        <td>$ <?= number_format($re_quote->get_total_cost(), 2) ?></td>
        <td></td>
        <td style="font-size:12pt;text-align:right;">$ <?= number_format($cotizacion->obtener_total_price(), 2) ?></td>
      </tr>
      <tr>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="font-size:12pt;">PROFIT:</td>
        <td style="font-size:12pt;text-align:right;">$ <?= number_format($cotizacion->obtener_total_price() - $re_quote->get_total_cost(), 2) ?></td>
      </tr>
      <tr>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="font-size:12pt;text-align:right;"><?= number_format(($cotizacion->obtener_total_price() - $re_quote->get_total_cost()) / $cotizacion->obtener_total_price() * 100, 2) ?>%</td>
      </tr>
    </table>
    <br>
    <?php if (count($re_quote_services)) : ?>
      <table class="tabla" style="width:100%;">
        <tr>
          <th class="quantity">#</th>
          <th>DESCRIPTION</th>
          <th class="quantity">QTY</th>
          <th>UNIT PRICE</th>
          <th>TOTAL PRICE</th>
        </tr>
        <?php
        foreach ($re_quote_services as $key => $re_quote_service) {
          echo ProposalRepository::print_service_pdf_re_quote($re_quote->get_services_payment_term(), $cotizacion->obtener_services_payment_term(), $re_quote_service, $services, $key + 1);
        }
        ?>
        <tr>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="font-size:12pt;">TOTAL:</td>
          <td style="font-size:12pt;text-align:right;">$ <?= number_format($total_services, 2) ?></td>
        </tr>
      </table>
    <?php endif; ?>
  <?php endif; ?>
</body>

</html>