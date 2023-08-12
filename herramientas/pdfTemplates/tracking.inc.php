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
  <h2 class="color" style="text-align:center;">TRACKING DETAILS</h2>
  <table class="tabla">
    <tr>
      <th style="font-size:9pt;">PROPOSAL #</th>
      <th style="font-size:9pt;">CONTRACT NUMBER</th>
    </tr>
    <tr>
      <td style="text-align:center;font-size:9pt;"><?= $cotizacion->obtener_id() ?></td>
      <td style="text-align:center;font-size:9pt;"><?= $cotizacion->obtener_contract_number() ?></ts>
    </tr>
  </table>
  <?php if (count($items)) : ?>
    <table class="tabla" style="width:100%;">
      <tr>
        <th class="quantity">#</th>
        <th>PROJECT ESPC.</th>
        <th class="quantity">QTY(ordered)</th>
        <th class="quantity">QTY(shipped)</th>
        <th>CARRIER</th>
        <th>TRACKING #</th>
        <th>DELIVERY DATE</th>
        <th>DUE DATE</th>
        <th>SIGNED BY</th>
        <th>COMMENT</th>
      </tr>
      <?php
      $a = 1;
      for ($i = 0; $i < count($items); $i++) :
        $item = $items[$i];
        $re_quote_item = $re_quote_items[$i];
        Conexion::abrir_conexion();
        $trackings = TrackingRepository::get_all_trackings_by_id_item(Conexion::obtener_conexion(), $item->obtener_id());
        Conexion::cerrar_conexion();
      ?>
        <tr>
          <td style="border-bottom: 0px solid;"><?= $a ?></td>
          <td style="border-bottom: 0px solid;">
            <b>Brand name:</b> <?= $re_quote_item->get_brand() ?>
            <br>
            <b>Part number:</b> <?= $re_quote_item->get_part_number() ?>
            <br>
            <b>Item description:</b> <?= nl2br(wordwrap(mb_substr($re_quote_item->get_description(), 0, 150), 70, '<br>', true)) ?>
          </td>
          <td style="text-align:right;border-bottom: 0px solid;"><?= $re_quote_item->get_quantity() ?></td>
          <?php if (count($trackings)) : ?>
            <td><?= $trackings[0]->get_quantity() ?></td>
            <td><?= $trackings[0]->get_carrier() ?></td>
            <td><?= nl2br($trackings[0]->get_tracking_number()) ?></td>
            <td><?= RepositorioComment::mysql_date_to_english_format($trackings[0]->get_delivery_date()) ?></td>
            <td><?= RepositorioComment::mysql_date_to_english_format($trackings[0]->get_due_date()) ?></td>
            <td><?= $trackings[0]->get_signed_by() ?></td>
            <td><?= nl2br($trackings[0]->get_comments()) ?></td>
        </tr>
        <?php
            for ($j = 1; $j < count($trackings); $j++) :
              $tracking = $trackings[$j];
        ?>
          <tr>
            <td style="border-top: 0px solid;border-bottom: 0px solid;"></td>
            <td style="border-top: 0px solid;border-bottom: 0px solid;"></td>
            <td style="border-top: 0px solid;border-bottom: 0px solid;"></td>
            <td><?= $tracking->get_quantity() ?></td>
            <td><?= $tracking->get_carrier() ?></td>
            <td><?= nl2br($tracking->get_tracking_number()) ?></td>
            <td><?= RepositorioComment::mysql_date_to_english_format($tracking->get_delivery_date()) ?></td>
            <td><?= RepositorioComment::mysql_date_to_english_format($tracking->get_due_date()) ?></td>
            <td><?= $tracking->get_signed_by() ?></td>
            <td><?= nl2br($tracking->get_comments()) ?></td>
          </tr>
        <?php endfor; ?>
      <?php endif; ?>
      <?php
        Conexion::abrir_conexion();
        $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
        $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item->get_id());
        Conexion::cerrar_conexion();
        if (count($subitems)) :
          for ($k = 0; $k < count($subitems); $k++) :
            $subitem = $subitems[$k];
            $re_quote_subitem = $re_quote_subitems[$k];
            Conexion::abrir_conexion();
            $trackings_subitems = TrackingSubitemRepository::get_all_trackings_by_id_subitem(Conexion::obtener_conexion(), $subitem->obtener_id());
            Conexion::cerrar_conexion();
      ?>
          <tr>
            <td style="border-bottom: 0px solid;"></td>
            <td style="border-bottom: 0px solid;">
              <b>Brand name:</b> <?= $re_quote_subitem->get_brand() ?>
              <br>
              <b>Part number:</b> <?= $re_quote_subitem->get_part_number() ?>
              <br>
              <b>Item description:</b>
              <br> <?= nl2br(wordwrap(mb_substr($re_quote_subitem->get_description(), 0, 150), 70, '<br>', true)) ?>
            </td>
            <td style="text-align:right;border-bottom: 0px solid;"><?= $re_quote_subitem->get_quantity() ?></td>
            <?php if (count($trackings_subitems)) : ?>
              <td><?= $trackings_subitems[0]->get_quantity() ?></td>
              <td><?= $trackings_subitems[0]->get_carrier() ?></td>
              <td><?= nl2br($trackings_subitems[0]->get_tracking_number()) ?></td>
              <td><?= RepositorioComment::mysql_date_to_english_format($trackings_subitems[0]->get_delivery_date()) ?></td>
              <td><?= RepositorioComment::mysql_date_to_english_format($trackings_subitems[0]->get_due_date()) ?></td>
              <td><?= $trackings_subitems[0]->get_signed_by() ?></td>
              <td><?= nl2br($trackings_subitems[0]->get_comments()) ?></td>
          </tr>
          <?php
              for ($l = 1; $l < count($trackings_subitems); $l++) :
                $tracking_subitem = $trackings_subitems[$l];
          ?>
            <tr>
              <td style="border-top: 0px solid;border-bottom: 0px solid;"></td>
              <td style="border-top: 0px solid;border-bottom: 0px solid;"></td>
              <td style="border-top: 0px solid;border-bottom: 0px solid;"></td>
              <td><?= $tracking_subitem->get_quantity() ?></td>
              <td><?= $tracking_subitem->get_carrier() ?></td>
              <td><?= nl2br($tracking_subitem->get_tracking_number()) ?></td>
              <td><?= RepositorioComment::mysql_date_to_english_format($tracking_subitem->get_delivery_date()) ?></td>
              <td><?= RepositorioComment::mysql_date_to_english_format($tracking_subitem->get_due_date()) ?></td>
              <td><?= $tracking_subitem->get_signed_by() ?></td>
              <td><?= nl2br($tracking_subitem->get_comments()) ?></td>
            </tr>
          <?php endfor; ?>
        <?php endif; ?>
      <?php endfor; ?>
    <?php
        endif;
        $a++;
    ?>
  <?php endfor; ?>
  <tr>
    <td colspan="10" style="text-align:center;">
      <b>E-Logic</b> wants you to be satisfied with your purchase, help us to achieve it. Before you accept the delivery of your items, please inspect it. If any issue exists, you may refuse delivery. Once you have accepted delivery you have 30 days to contact us regarding defects, damage or other issues. <b>E-Logic</b> will not be able to arrange a replacement once this period has elapsed.
    </td>
  </tr>
    </table>
  <?php endif; ?>
</body>

</html>