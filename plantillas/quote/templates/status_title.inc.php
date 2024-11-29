<?php
$statusIcons = [
  'submitted_invoice' => 'Submitted Invoice',
  'invoice' => 'Invoice',
  'fulfillment' => 'Fulfillment',
  'award' => 'Award',
  'status' => 'Submitted',
  'completed' => 'Completed'
];

$statusUrls = [
  'submitted_invoice' => REMOVE_SUBMITTED_INVOICE,
  'invoice' => REMOVE_INVOICE,
  'fulfillment' => REMOVE_FULFILLMENT,
  'award' => REMOVE_AWARD
];

function renderStatus($icon, $text, $url = null, $date = null) {
  echo '<h1 class="float-right text-success"><i class="fa fa-check"></i> ' . $text . '</h1>';
  echo '<div class="clearfix"></div>';
  if ($date) {
    echo '<span class="text-secondary float-right">' . $date . '</span>';
    echo '<div class="clearfix"></div>';
  }
  if ($url) {
    echo '<a href="' . $url . '" class="float-right d-block"><i class="fas fa-times"></i> Remove ' . $text . ' Status</a>';
  }
}

if ($cotizacion_recuperada->obtener_submitted_invoice()) {
  renderStatus($statusIcons['submitted_invoice'], 'Submitted Invoice', $statusUrls['submitted_invoice'] . $cotizacion_recuperada->obtener_id());
} elseif ($cotizacion_recuperada->obtener_invoice()) {
  $invoiceDate = date("m/d/Y", strtotime($cotizacion_recuperada->obtener_invoice_date()));
  renderStatus($statusIcons['invoice'], 'Invoice', $statusUrls['invoice'] . $cotizacion_recuperada->obtener_id(), $invoiceDate);
} elseif ($cotizacion_recuperada->obtener_fullfillment()) {
  renderStatus($statusIcons['fulfillment'], 'Fulfillment', $statusUrls['fulfillment'] . $cotizacion_recuperada->obtener_id());
} elseif ($cotizacion_recuperada->obtener_award()) {
  renderStatus($statusIcons['award'], 'Award', $statusUrls['award'] . $cotizacion_recuperada->obtener_id());
} elseif ($cotizacion_recuperada->obtener_status()) {
  renderStatus($statusIcons['status'], 'Submitted');
} elseif ($cotizacion_recuperada->obtener_completado()) {
  renderStatus($statusIcons['completed'], 'Completed');
}
