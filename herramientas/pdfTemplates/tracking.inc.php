<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: Roboto, sans-serif;
      font-size: 9pt;
      color: #2d3748;
      line-height: 1.4;
    }

    /* ── Items table ── */
    .items-table {
      width: 100%;
      border-collapse: collapse;
    }

    .items-table thead th {
      background: #004A97;
      color: #fff;
      font-size: 6.5pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      padding: 7px 5px;
      border: none;
      text-align: center;
    }

    .items-table thead th.desc-col {
      text-align: left;
    }

    .items-table tbody td {
      padding: 6px 5px;
      border: 1px solid #e5edf5;
      vertical-align: top;
      font-size: 7pt;
      color: #2d3748;
      background: #fff;
    }

    .items-table .subitem-row td {
      background: #fafcff;
      font-size: 7pt;
    }

    .items-table .continuation-row td {
      border-top: none;
      border-bottom: 1px solid #e5edf5;
    }

    /* ── Disclaimer row ── */
    .disclaimer-row td {
      background: #F7FAFD !important;
      border-top: 2px solid #004A97 !important;
      border-bottom: none;
      border-left: none;
      border-right: none;
      font-size: 7.5pt;
      color: #4a6080;
      padding: 10px 8px;
      text-align: center;
    }
  </style>
</head>

<body>

  <!-- ── Top accent bar ── -->
  <div style="background:#004A97; height:5px; margin-bottom:14px;"></div>

  <!-- ── Page title ── -->
  <table style="width:100%; border-collapse:collapse; margin-bottom:14px;" cellspacing="0" cellpadding="0">
    <tr>
      <td style="vertical-align:middle;">
        <div style="font-size:16pt; font-weight:700; color:#004A97; letter-spacing:3px;">TRACKING DETAILS</div>
        <div style="font-size:8pt; color:#8fa8c4; margin-top:2px; text-transform:uppercase; letter-spacing:0.5px;">Shipment &amp; Delivery Reference</div>
      </td>
      <td style="vertical-align:middle; text-align:right;">
        <table style="border-collapse:collapse; margin-left:auto;" cellspacing="0" cellpadding="0">
          <tr>
            <td style="padding:4px 12px; background:#004A97; color:#fff; font-size:6.5pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; border-right:1px solid #2d6bbf;">Proposal #</td>
            <td style="padding:4px 12px; background:#004A97; color:#fff; font-size:6.5pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;">Contract Number</td>
          </tr>
          <tr>
            <td style="padding:5px 12px; border:1px solid #dce8f5; font-size:9pt; font-weight:700; text-align:center; color:#004A97;"><?= htmlspecialchars($cotizacion->obtener_id()) ?></td>
            <td style="padding:5px 12px; border:1px solid #dce8f5; border-left:none; font-size:8pt; text-align:center; color:#2d3748;"><?= htmlspecialchars($cotizacion->obtener_contract_number()) ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- ── Tracking table ── -->
  <?php if (count($items)) : ?>
    <table class="items-table" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th style="width:18px;">#</th>
          <th class="desc-col">Project Spec.</th>
          <th style="width:28px;">Qty<br>Ordered</th>
          <th style="width:28px;">Qty<br>Shipped</th>
          <th>Carrier</th>
          <th>Tracking #</th>
          <th>Delivery Date</th>
          <th>Due Date</th>
          <th>Signed By</th>
          <th>Comment</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $a = 1;
        for ($i = 0; $i < count($items); $i++) :
          $item          = $items[$i];
          $re_quote_item = $re_quote_items[$i];
          Conexion::abrir_conexion();
          $trackings = TrackingRepository::get_all_trackings_by_id_item(Conexion::obtener_conexion(), $item->obtener_id());
          Conexion::cerrar_conexion();
        ?>
          <tr>
            <td style="border-bottom:none;"><?= $a ?></td>
            <td style="border-bottom:none;">
              <b>Brand:</b> <?= htmlspecialchars($re_quote_item->get_brand()) ?><br>
              <b>Part #:</b> <?= htmlspecialchars($re_quote_item->get_part_number()) ?><br>
              <b>Description:</b> <?= nl2br(htmlspecialchars(mb_substr($re_quote_item->get_description(), 0, 150))) ?>
            </td>
            <td style="text-align:center; border-bottom:none;"><?= htmlspecialchars($re_quote_item->get_quantity()) ?></td>
            <?php if (count($trackings)) : ?>
              <td style="text-align:center;"><?= htmlspecialchars($trackings[0]->get_quantity()) ?></td>
              <td><?= htmlspecialchars($trackings[0]->get_carrier()) ?></td>
              <td><?= nl2br(htmlspecialchars($trackings[0]->get_tracking_number())) ?></td>
              <td style="text-align:center;"><?= htmlspecialchars(RepositorioComment::mysql_date_to_english_format($trackings[0]->get_delivery_date())) ?></td>
              <td style="text-align:center;"><?= htmlspecialchars(RepositorioComment::mysql_date_to_english_format($trackings[0]->get_due_date())) ?></td>
              <td><?= htmlspecialchars($trackings[0]->get_signed_by()) ?></td>
              <td><?= nl2br(htmlspecialchars($trackings[0]->get_comments())) ?></td>
            </tr>
            <?php for ($j = 1; $j < count($trackings); $j++) :
              $tracking = $trackings[$j]; ?>
              <tr class="continuation-row">
                <td style="border-top:none; border-bottom:none;"></td>
                <td style="border-top:none; border-bottom:none;"></td>
                <td style="border-top:none; border-bottom:none;"></td>
                <td style="text-align:center;"><?= htmlspecialchars($tracking->get_quantity()) ?></td>
                <td><?= htmlspecialchars($tracking->get_carrier()) ?></td>
                <td><?= nl2br(htmlspecialchars($tracking->get_tracking_number())) ?></td>
                <td style="text-align:center;"><?= htmlspecialchars(RepositorioComment::mysql_date_to_english_format($tracking->get_delivery_date())) ?></td>
                <td style="text-align:center;"><?= htmlspecialchars(RepositorioComment::mysql_date_to_english_format($tracking->get_due_date())) ?></td>
                <td><?= htmlspecialchars($tracking->get_signed_by()) ?></td>
                <td><?= nl2br(htmlspecialchars($tracking->get_comments())) ?></td>
              </tr>
            <?php endfor; ?>
          <?php else : ?>
              <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
          <?php endif; ?>

          <?php
          Conexion::abrir_conexion();
          $subitems          = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
          $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item->get_id());
          Conexion::cerrar_conexion();
          if (count($subitems)) :
            for ($k = 0; $k < count($subitems); $k++) :
              $subitem          = $subitems[$k];
              $re_quote_subitem = $re_quote_subitems[$k];
              Conexion::abrir_conexion();
              $trackings_subitems = TrackingSubitemRepository::get_all_trackings_by_id_subitem(Conexion::obtener_conexion(), $subitem->obtener_id());
              Conexion::cerrar_conexion();
          ?>
            <tr class="subitem-row">
              <td style="border-bottom:none;"></td>
              <td style="border-bottom:none;">
                <b>Brand:</b> <?= htmlspecialchars($re_quote_subitem->get_brand()) ?><br>
                <b>Part #:</b> <?= htmlspecialchars($re_quote_subitem->get_part_number()) ?><br>
                <b>Description:</b> <?= nl2br(htmlspecialchars(mb_substr($re_quote_subitem->get_description(), 0, 150))) ?>
              </td>
              <td style="text-align:center; border-bottom:none;"><?= htmlspecialchars($re_quote_subitem->get_quantity()) ?></td>
              <?php if (count($trackings_subitems)) : ?>
                <td style="text-align:center;"><?= htmlspecialchars($trackings_subitems[0]->get_quantity()) ?></td>
                <td><?= htmlspecialchars($trackings_subitems[0]->get_carrier()) ?></td>
                <td><?= nl2br(htmlspecialchars($trackings_subitems[0]->get_tracking_number())) ?></td>
                <td style="text-align:center;"><?= htmlspecialchars(RepositorioComment::mysql_date_to_english_format($trackings_subitems[0]->get_delivery_date())) ?></td>
                <td style="text-align:center;"><?= htmlspecialchars(RepositorioComment::mysql_date_to_english_format($trackings_subitems[0]->get_due_date())) ?></td>
                <td><?= htmlspecialchars($trackings_subitems[0]->get_signed_by()) ?></td>
                <td><?= nl2br(htmlspecialchars($trackings_subitems[0]->get_comments())) ?></td>
              </tr>
              <?php for ($l = 1; $l < count($trackings_subitems); $l++) :
                $tracking_subitem = $trackings_subitems[$l]; ?>
                <tr class="subitem-row continuation-row">
                  <td style="border-top:none; border-bottom:none;"></td>
                  <td style="border-top:none; border-bottom:none;"></td>
                  <td style="border-top:none; border-bottom:none;"></td>
                  <td style="text-align:center;"><?= htmlspecialchars($tracking_subitem->get_quantity()) ?></td>
                  <td><?= htmlspecialchars($tracking_subitem->get_carrier()) ?></td>
                  <td><?= nl2br(htmlspecialchars($tracking_subitem->get_tracking_number())) ?></td>
                  <td style="text-align:center;"><?= htmlspecialchars(RepositorioComment::mysql_date_to_english_format($tracking_subitem->get_delivery_date())) ?></td>
                  <td style="text-align:center;"><?= htmlspecialchars(RepositorioComment::mysql_date_to_english_format($tracking_subitem->get_due_date())) ?></td>
                  <td><?= htmlspecialchars($tracking_subitem->get_signed_by()) ?></td>
                  <td><?= nl2br(htmlspecialchars($tracking_subitem->get_comments())) ?></td>
                </tr>
              <?php endfor; ?>
              <?php else : ?>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <?php endif; ?>
          <?php endfor; ?>
          <?php endif; ?>
          <?php $a++; ?>
        <?php endfor; ?>

        <tr class="disclaimer-row">
          <td colspan="10">
            <b style="color:#004A97;">E-Logic</b> wants you to be satisfied with your purchase, help us to achieve it.
            Before you accept the delivery of your items, please inspect it. If any issue exists, you may refuse delivery.
            Once you have accepted delivery you have 30 days to contact us regarding defects, damage or other issues.
            <b style="color:#004A97;">E-Logic</b> will not be able to arrange a replacement once this period has elapsed.
          </td>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>

</body>
</html>
