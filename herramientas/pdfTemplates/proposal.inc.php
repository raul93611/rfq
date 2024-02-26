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
        <img style="width:200px;height:200px;" src="img/<?= $logo ?>">
      </td>
      <td align="right">
        <span class="color letra_grande">PROPOSAL</span>
        <br><br>
        <table id="tabla">
          <tr>
            <th>EXPIRATION DATE</th>
          </tr>
          <tr>
            <td style="text-align:center;"><?= $expiration_date ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <table id="tabla" style="width:100%">
    <tr>
      <th>CLIENT NAME</th>
    </tr>
    <tr>
      <td style="text-align:center;">Olzaa Agencia de Aduana SA</td>
    </tr>
  </table>
  <br>
  <table id="tabla" style="width:100%">
    <tr>
      <th class="quantity">#</th>
      <th>DESCRIPTION</th>
      <th class="quantity">QTY</th>
      <th>UNIT PRICE</th>
      <th class="total_ancho">TOTAL</th>
    </tr>
    <?php if ($encabezado) : ?>
      <tr>
        <td></td>
        <td>
          OPEN MARKET PRICING PROPOSAL<br><br>
          E-Logic is an SBA 8(a) and HUBZONE Certified SB<br><br>
          SBA 8(a) Case Number: C0069X<br><br>
          SBA 8(a) Entrance Date: 09/30/2016<br><br>
          SBA 8(a) Exit Date: 09/30/2026
        </td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    <?php endif; ?>
    <?php
    $a = 1;
    $limit = 400;
    if (count($items)) {
      foreach ($items as $i => $item) {
        echo ProposalRepository::print_item($item, $limit, $a);
        $a++;
      }
    }
    if (isset($services)) {
      foreach ($services as $key => $service) {
        echo ProposalRepository::print_service($cotizacion->obtener_services_payment_term(), $service, $a);
        $a++;
      }
    }
    ?>
    <tr>
      <td style="border-bottom:0;border-left:0;"></td>
      <td colspan="3" style="font-size:10pt;"><?= nl2br($cotizacion->obtener_shipping()) ?></td>
      <td style="text-align:right;">$ <?= number_format($cotizacion->obtener_shipping_cost(), 2) ?></td>
    </tr>
    <tr>
      <td style="border:none;"></td>
      <td style="border:none;"></td>
      <td style="border:none;"></td>
      <td style="font-size:12pt;">TOTAL:</td>
      <td style="font-size:12pt;text-align:right;">$ <?= number_format($cotizacion->obtener_total_price() + $total_service, 2) ?></td>
    </tr>
  </table>
  <?php if ($payment_terms == 'Net 30') : ?>
    <br>
    <!-- <div class="color letra_chiquita"><b>PAYMENT TERMS</b><br><b>NET TERMS: </b>30 Days<br><b>CREDIT CARD PAYMENT: </b>Please add an additional 3% to process credit card payments.</div> -->
  <?php endif; ?>
</body>

</html>