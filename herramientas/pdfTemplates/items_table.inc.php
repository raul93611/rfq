<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Proposal Details</title>
  <style>
    body {
      font-family: Roboto, sans-serif;
    }

    th {
      color: #004A97;
      background-color: #DEE8F2;
    }

    .tabla th,
    .tabla td {
      border: 1px solid #DEE8F2;
      padding: 5px 10px;
      font-size: 0.75rem;
    }

    table {
      width: 100%;
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
      font-size: 0.875rem;
    }

    .color {
      color: #004A97;
    }

    .letra_grande {
      font-size: 1.125rem;
    }

    .tabla th,
    .tabla td {
      text-align: center;
    }

    .right-align {
      text-align: right;
    }
  </style>
</head>

<body>
  <table>
    <tr>
      <td>
        <table class="tabla">
          <thead>
            <tr>
              <th scope="col">SHIP VIA</th>
              <th scope="col">CONTRACT NUMBER</th>
              <th scope="col">SALES REP</th>
              <th scope="col">E-MAIL</th>
              <th scope="col">PAYMENT TERMS</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?= htmlspecialchars($cotizacion->obtener_ship_via()) ?></td>
              <td><?= htmlspecialchars($cotizacion->obtener_email_code()) ?></td>
              <td><?= htmlspecialchars($usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos()) ?></td>
              <td><?= htmlspecialchars($usuario_designado->obtener_email()) ?></td>
              <td><?= htmlspecialchars($cotizacion->obtener_payment_terms()) ?></td>
            </tr>
          </tbody>
        </table>
      </td>
      <td>
        <table class="tabla">
          <thead>
            <tr>
              <th scope="col">PROPOSAL #</th>
              <th scope="col">DATE</th>
              <th scope="col">EXPIRATION DATE</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?= htmlspecialchars($cotizacion->obtener_id()) ?></td>
              <td><?= htmlspecialchars($fecha_completado) ?></td>
              <td><?= htmlspecialchars($expiration_date) ?></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>

  <div class="color">
    <b>Taxes:</b> <?= htmlspecialchars($cotizacion->obtener_taxes()) ?>%<br>
    <b>Profit:</b> <?= htmlspecialchars($cotizacion->obtener_profit()) ?>%<br>
    <b>Additional general: </b> $ <?= number_format($cotizacion->obtener_additional(), 2) ?>
  </div>

  <br>

  <?php if (count($items)) : ?>
    <table class="tabla">
      <thead>
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
      </thead>
      <tbody>
        <?php
        $a = 1;
        $payment_terms = ($cotizacion->obtener_payment_terms() == 'Net 30/CC') ? 1.0299 : 1;

        foreach ($items as $item) {
          echo ProposalRepository::print_item_pdf($cotizacion, $item, $a, $payment_terms);
          $a++;
        }
        ?>
        <tr>
          <td style="border:none;"></td>
          <td colspan="8" class="letra_chiquita"><?= nl2br(htmlspecialchars($cotizacion->obtener_shipping())) ?></td>
          <td class="right-align">$ <?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
        </tr>
        <tr>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="font-size:1rem;">TOTAL:</td>
          <td>$ <?= number_format($cotizacion->obtener_total_cost(), 2) ?></td>
          <td></td>
          <td class="right-align" style="font-size:1rem;">$ <?= number_format($cotizacion->obtener_total_price(), 2) ?></td>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>

  <?php if ($cotizacion->obtener_payment_terms() == 'Net 30') : ?>
    <br>
    <div class="color letra_chiquita">
      <b>PAYMENT TERMS</b><br>
      <b>NET TERMS: </b>30 Days<br>
      <b>CREDIT CARD PAYMENT: </b>Please add an additional 2.1% to process credit card payments.
    </div>
  <?php endif; ?>
</body>

</html>