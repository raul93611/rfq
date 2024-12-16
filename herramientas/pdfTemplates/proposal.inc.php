<!DOCTYPE html>
<html>

<head>
  <style>
    body {
      font-family: Roboto, sans-serif;
    }

    th {
      color: #004A97;
      background-color: #DEE8F2;
    }

    #tabla {
      width: 100%;
      border-collapse: collapse;
    }

    #tabla th,
    #tabla td {
      border: 1px solid #DEE8F2;
      padding: 10px;
      font-size: 9pt;
    }

    td {
      color: #3B3B3B;
    }

    .quantity {
      width: 20px;
      text-align: center;
    }

    .unit_price {
      width: 100px;
      text-align: right;
    }

    .total_ancho {
      width: 130px;
      text-align: right;
    }

    .letra_chiquita {
      font-size: 8pt;
    }

    .color {
      color: #004A97;
    }

    .letra_grande {
      font-size: 25pt;
      text-align: center;
    }
  </style>
</head>

<body>
  <table border="0" style="width:100%;">
    <tr>
      <td style="width:400px;">
        <img style="width:350px;height:130px;" src="img/<?= htmlspecialchars($logo) ?>" alt="Company Logo">
      </td>
      <td align="right">
        <span class="color letra_grande">PROPOSAL</span>
        <br><br>
        <table id="tabla">
          <tr>
            <th>PROPOSAL #</th>
            <th>DATE</th>
            <th>EXPIRATION DATE</th>
          </tr>
          <tr>
            <td><?= htmlspecialchars($cotizacion->obtener_id()) ?></td>
            <td><?= htmlspecialchars($fecha_completado) ?></td>
            <td><?= htmlspecialchars($expiration_date) ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <table id="tabla">
    <tr>
      <th>ADDRESS</th>
      <th>SHIP TO</th>
    </tr>
    <tr>
      <td><?= nl2br(htmlspecialchars($cotizacion->obtener_address())) ?></td>
      <td><?= nl2br(htmlspecialchars($cotizacion->obtener_ship_to())) ?></td>
    </tr>
  </table>
  <br>
  <table id="tabla">
    <tr>
      <th>SHIP VIA</th>
      <th>CONTRACT NUMBER</th>
      <th>SALES REP</th>
      <th>E-MAIL</th>
      <th>PAYMENT TERMS</th>
    </tr>
    <tr>
      <td><?= htmlspecialchars($cotizacion->obtener_ship_via()) ?></td>
      <td><?= htmlspecialchars($cotizacion->obtener_email_code()) ?></td>
      <td><?= htmlspecialchars($usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos()) ?></td>
      <td><?= htmlspecialchars($usuario_designado->obtener_email()) ?></td>
      <td><?= htmlspecialchars($payment_terms) ?></td>
    </tr>
  </table>
  <br>
  <table id="tabla">
    <tr>
      <th class="quantity">#</th>
      <th>DESCRIPTION</th>
      <th class="quantity">QTY</th>
      <th class="unit_price">UNIT PRICE</th>
      <th class="total_ancho">TOTAL</th>
    </tr>
    <?php if ($encabezado) : ?>
      <tr>
        <td></td>
        <td colspan="4">
          OPEN MARKET PRICING PROPOSAL<br>
          E-Logic is an SBA 8(a) and HUBZONE Certified SB<br>
          SBA 8(a) Case Number: C0069X<br>
          SBA 8(a) Entrance Date: 09/30/2016<br>
          SBA 8(a) Exit Date: 09/30/2026
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
    <tr>
      <td colspan="4"><?= nl2br($cotizacion->obtener_shipping()) ?></td>
      <td style="text-align:right">$ <?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
    </tr>
    <tr>
      <td colspan="4" style="font-size:12pt; text-align:right;">TOTAL:</td>
      <td style="font-size:12pt;">$ <?= number_format($cotizacion->obtener_total_price() + $total_service, 2) ?></td>
    </tr>
  </table>
  <?php if ($payment_terms === 'Net 30') : ?>
    <br>
    <div class="color letra_chiquita">
      <b>PAYMENT TERMS</b><br>
      NET TERMS: 30 Days<br>
      CREDIT CARD PAYMENT: Please add an additional 3% to process credit card payments.
    </div>
  <?php endif; ?>
</body>

</html>