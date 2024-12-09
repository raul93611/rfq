<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Re-Quote Proposal</title>
  <style>
    body {
      font-family: Roboto, Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    h2 {
      color: #004A97;
      text-align: center;
    }

    .tabla {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 7pt;
    }

    .tabla th {
      background-color: #DEE8F2;
      color: #004A97;
      padding: 10px;
    }

    .tabla td {
      border: 1px solid #DEE8F2;
      padding: 5px;
      color: #3B3B3B;
      text-align: center;
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

    .letra_grande {
      font-size: 15pt;
      color: #004A97;
    }
  </style>
</head>

<body>
  <h2>RE-QUOTE</h2>

  <!-- Shipping Details -->
  <table class="tabla">
    <tr>
      <th>SHIP VIA</th>
      <th>CONTRACT NUMBER</th>
      <th>SALES REP</th>
      <th>E-MAIL</th>
      <th>PAYMENT TERMS</th>
    </tr>
    <tr>
      <td><?= $cotizacion->obtener_ship_via() ?></td>
      <td><?= $cotizacion->obtener_email_code() ?></td>
      <td><?= $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos() ?></td>
      <td><?= $usuario_designado->obtener_email() ?></td>
      <td><?= $re_quote->get_payment_terms() ?></td>
    </tr>
  </table>

  <!-- Proposal Details -->
  <table class="tabla">
    <tr>
      <th>PROPOSAL #</th>
      <th>DATE</th>
      <th>EXPIRATION DATE</th>
    </tr>
    <tr>
      <td><?= $cotizacion->obtener_id() ?></td>
      <td><?= $fecha_completado ?></td>
      <td><?= $expiration_date ?></td>
    </tr>
  </table>

  <!-- Re-Quote Items -->
  <?php if (!empty($re_quote_items)): ?>
    <table class="tabla">
      <tr>
        <th class="quantity">#</th>
        <th>PROJECT SPEC.</th>
        <th>E-LOGIC PROP.</th>
        <th class="quantity">QTY</th>
        <th>PROVIDER</th>
        <th>BEST UNIT COST</th>
        <th>TOTAL COST</th>
        <th>PRICE FOR CLIENT</th>
        <th>TOTAL PRICE</th>
      </tr>
      <?php foreach ($re_quote_items as $key => $re_quote_item): ?>
        <?= ProposalRepository::print_item_pdf_re_quote($re_quote_item, $items, $key + 1, $key) ?>
      <?php endforeach; ?>
      <tr>
        <td colspan="6" style="text-align: left;"><?= nl2br($re_quote->get_shipping()) ?></td>
        <td>$<?= number_format($re_quote->get_shipping_cost(), 2) ?></td>
        <td></td>
        <td>$<?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
      </tr>
      <tr>
        <td colspan="5" style="border: none;"></td>
        <td>TOTAL:</td>
        <td>$<?= number_format($re_quote->get_total_cost(), 2) ?></td>
        <td></td>
        <td>$<?= number_format($cotizacion->obtener_total_price(), 2) ?></td>
      </tr>
      <tr>
        <td colspan="7" style="border: none;"></td>
        <td>PROFIT:</td>
        <td>$<?= number_format($cotizacion->obtener_total_price() - $re_quote->get_total_cost(), 2) ?></td>
      </tr>
      <tr>
        <td colspan="8" style="border: none;"></td>
        <td><?= number_format(($cotizacion->obtener_total_price() - $re_quote->get_total_cost()) / $cotizacion->obtener_total_price() * 100, 2) ?>%</td>
      </tr>
    </table>
  <?php endif; ?>

  <!-- Re-Quote Services -->
  <?php if (!empty($re_quote_services)): ?>
    <table class="tabla">
      <tr>
        <th class="quantity">#</th>
        <th>DESCRIPTION</th>
        <th class="quantity">QTY</th>
        <th>UNIT PRICE</th>
        <th>TOTAL PRICE</th>
      </tr>
      <?php foreach ($re_quote_services as $key => $re_quote_service): ?>
        <?= ProposalRepository::print_service_pdf_re_quote($re_quote->get_services_payment_term(), $cotizacion->obtener_services_payment_term(), $re_quote_service, $key + 1) ?>
      <?php endforeach; ?>
      <tr>
        <td colspan="3" style="border: none;"></td>
        <td>TOTAL:</td>
        <td>$<?= number_format($total_services, 2) ?></td>
      </tr>
    </table>
  <?php endif; ?>
</body>

</html>