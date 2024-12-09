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

    .tabla th,
    .tabla td {
      border: 1px solid #DEE8F2;
      padding: 5px 10px;
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

    .letra_grande {
      font-size: 15pt;
    }

    .color {
      color: #004A97;
    }
  </style>
</head>

<body>
  <h2 class="color" style="text-align:center;">TRACKING DETAILS</h2>

  <!-- Proposal and Contract Table -->
  <table class="tabla">
    <tr>
      <th style="font-size:9pt;">PROPOSAL #</th>
      <th style="font-size:9pt;">CONTRACT NUMBER</th>
    </tr>
    <tr>
      <td style="text-align:center;font-size:9pt;"><?= $cotizacion->obtener_id() ?></td>
      <td style="text-align:center;font-size:9pt;"><?= $cotizacion->obtener_contract_number() ?></td>
    </tr>
  </table>

  <?php if (!empty($items)) : ?>
    <!-- Items Table -->
    <table class="tabla" style="width:100%; margin-top: 20px;">
      <tr>
        <th class="quantity">#</th>
        <th>PROJECT SPEC.</th>
        <th class="quantity">QTY (Ordered)</th>
        <th class="quantity">QTY (Shipped)</th>
        <th>CARRIER</th>
        <th>TRACKING #</th>
        <th>DELIVERY DATE</th>
        <th>DUE DATE</th>
        <th>SIGNED BY</th>
        <th>COMMENT</th>
      </tr>

      <?php
      $a = 1;
      Conexion::abrir_conexion();
      foreach ($items as $i => $item) :
        $re_quote_item = $re_quote_items[$i];
        $trackings = TrackingRepository::get_all_trackings_by_id_item(Conexion::obtener_conexion(), $item->obtener_id());
        $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
        $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item->get_id());
      ?>

        <!-- Item Row -->
        <?= renderTrackingRow($a++, $re_quote_item, $trackings) ?>

        <!-- Subitem Rows -->
        <?php foreach ($subitems as $k => $subitem) :
          $re_quote_subitem = $re_quote_subitems[$k];
          $trackings_subitems = TrackingSubitemRepository::get_all_trackings_by_id_subitem(Conexion::obtener_conexion(), $subitem->obtener_id());
        ?>
          <?= renderTrackingRow(null, $re_quote_subitem, $trackings_subitems, true) ?>
        <?php endforeach; ?>

      <?php endforeach;
      Conexion::cerrar_conexion();
      ?>
      <!-- Footer Note -->
      <tr>
        <td colspan="10" style="text-align:center;">
          <b>E-Logic</b> wants you to be satisfied with your purchase. Before accepting delivery, please inspect the items. If there are issues, you may refuse delivery. After acceptance, you have 30 days to contact us regarding defects or damage. Replacement requests cannot be processed after this period.
        </td>
      </tr>
    </table>
  <?php endif; ?>
</body>

</html>

<?php
/**
 * Renders tracking rows for items and subitems.
 *
 * @param int|null $index Index number or null for subitems.
 * @param object $quoteItem The quote item or subitem object.
 * @param array $trackings The list of trackings for the item or subitem.
 * @param bool $isSubitem Whether this is a subitem row.
 * @return string Rendered HTML rows.
 */
function renderTrackingRow($index, $quoteItem, $trackings, $isSubitem = false) {
  ob_start();
?>
  <tr>
    <td style="text-align:center;"><?= $index ?? '' ?></td>
    <td>
      <b>Brand name:</b> <?= $quoteItem->get_brand() ?><br>
      <b>Part number:</b> <?= $quoteItem->get_part_number() ?><br>
      <b>Item description:</b> <?= nl2br(wordwrap(mb_substr($quoteItem->get_description(), 0, 150), 70, '<br>', true)) ?>
    </td>
    <td style="text-align:right;"><?= $quoteItem->get_quantity() ?></td>
    <?php if (!empty($trackings)) : ?>
      <?= renderTrackingDetails($trackings[0]) ?>
  </tr>
  <?php for ($j = 1; $j < count($trackings); $j++) : ?>
    <tr>
      <td colspan="<?= $isSubitem ? 3 : 2 ?>"></td>
      <?= renderTrackingDetails($trackings[$j]) ?>
    </tr>
  <?php endfor; ?>
<?php else : ?>
  <td colspan="7" style="text-align:center;">No tracking details available.</td>
<?php endif; ?>
<?php
  return ob_get_clean();
}

/**
 * Renders tracking details for a single tracking entry.
 *
 * @param object $tracking Tracking object.
 * @return string Rendered HTML cells.
 */
function renderTrackingDetails($tracking) {
  return sprintf(
    '<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
    $tracking->get_quantity(),
    $tracking->get_carrier(),
    nl2br($tracking->get_tracking_number()),
    RepositorioComment::mysql_date_to_english_format($tracking->get_delivery_date()),
    RepositorioComment::mysql_date_to_english_format($tracking->get_due_date()),
    nl2br($tracking->get_comments())
  );
}
?>