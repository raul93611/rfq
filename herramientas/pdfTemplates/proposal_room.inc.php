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

    .unit_price {
      width: 100px;
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
        <span class="color letra_grande">PROPOSAL</span>
        <br><br>
        <table id="tabla">
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
  <br>
  <table id="tabla" style="width:100%">
    <tr>
      <th style="width:50%">ADDRESS</th>
      <th style="width:50%">SHIP TO</th>
    </tr>
    <tr>
      <td><?= nl2br($cotizacion->obtener_address()) ?></td>
      <td><?= nl2br($cotizacion->obtener_ship_to()) ?></td>
    </tr>
  </table>
  <br>
  <table id="tabla" style="width:100%">
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
      <td style="text-align:center;"><?= $payment_terms ?></td>
    </tr>
  </table>
  <br>
  <?php foreach ($rooms as $key => $room) : ?>
    <span class="color letra_grande"><?= strtoupper($room->getName()) ?></span>
    <br>
    <table id="tabla" style="width:100%">
      <tr>
        <th class="quantity">#</th>
        <th>DESCRIPTION</th>
        <th class="quantity">QTY</th>
        <th class="unit_price">UNIT PRICE</th>
        <th class="total_ancho">TOTAL</th>
      </tr>
      <?php
      $a = 1;
      $limit = 400;
      if (count($items[$room->getName()])) {
        foreach ($items[$room->getName()] as $i => $item) {
          echo ProposalRepository::print_item($item, $limit, $a);
          $a++;
        }
      }
      if (isset($services[$room->getName()])) {
        foreach ($services[$room->getName()] as $key => $service) {
          echo ProposalRepository::print_service($cotizacion->obtener_services_payment_term(), $service, $a);
          $a++;
        }
      }
      ?>
      <tr>
        <td style="border-bottom: 0;border-left: 0;border-right: 0;"></td>
        <td style="border-bottom: 0;border-left: 0;border-right: 0;"></td>
        <td style="border-bottom: 0;border-left: 0;border-right: 0;"></td>
        <td style="border-bottom: 0;border-left: 0;border-right: 0;"></td>
        <td style="border-bottom: 0;border-left: 0;border-right: 0;"></td>
      </tr>
    </table>
  <?php endforeach; ?>
  <br>
  <table id="tabla" style="width:100%">
    <tr>
      <td colspan="6" style="font-size:10pt;"><?= nl2br($cotizacion->obtener_shipping()) ?></td>
      <td style="text-align:right;">$ <?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
    </tr>
    <tr>
      <td colspan="6" style="font-size:12pt;">TOTAL:</td>
      <td style="font-size:12pt;text-align:right;">$ <?= number_format($cotizacion->obtener_total_price() + $total_service, 2) ?></td>
    </tr>
  </table>
  <?php if ($payment_terms == 'Net 30') : ?>
    <br>
    <div class="color letra_chiquita"><b>PAYMENT TERMS</b><br><b>NET TERMS: </b>30 Days<br><b>CREDIT CARD PAYMENT: </b>Please add an additional 3% to process credit card payments.</div>
  <?php endif; ?>
</body>

</html>