<?php
$statusColors = [
  'Submitted Invoice'    => '#8e44ad',
  'Invoice'              => '#27ae60',
  'Fulfillment'          => '#2980b9',
  'Award'                => '#e67e22',
  'Sources Sought'       => '#14b8a6',
  'Submitted'            => '#13A8F0',
  'No Award - Pricing'   => '#dc2626',
  'No Award - Technical' => '#f0734f',
  'Completed'            => '#16a085',
];

$statusUrls = [
  'submitted_invoice' => REMOVE_SUBMITTED_INVOICE,
  'invoice'           => REMOVE_INVOICE,
  'fulfillment'       => REMOVE_FULFILLMENT,
  'award'             => REMOVE_AWARD,
];

function renderStatus($text, $url = null, $date = null, $icon = 'fa-check') {
  global $statusColors;
  $color = $statusColors[$text] ?? '#6c757d';
  echo '<div style="text-align:right;">';
  echo '<span style="display:inline-flex;align-items:center;gap:6px;background:' . $color . ';color:#fff;'
     . 'padding:5px 14px;border-radius:20px;font-size:13px;font-weight:600;font-family:Manrope,sans-serif;">'
     . '<i class="fa ' . htmlspecialchars($icon) . '"></i> ' . htmlspecialchars($text) . '</span>';
  if ($date) {
    echo '<div style="font-size:12px;color:#8896a5;margin-top:4px;">' . $date . '</div>';
  }
  if ($url) {
    echo '<div style="margin-top:4px;"><a href="' . $url . '" style="font-size:12px;color:#dc3545;">'
       . '<i class="fas fa-times mr-1"></i>Remove ' . htmlspecialchars($text) . '</a></div>';
  }
  echo '</div>';
}

$comments = $cotizacion_recuperada->obtener_comments();

if ($cotizacion_recuperada->obtener_submitted_invoice()) {
  renderStatus('Submitted Invoice', $statusUrls['submitted_invoice'] . $cotizacion_recuperada->obtener_id());
} elseif ($cotizacion_recuperada->obtener_invoice()) {
  $invoiceDate = date("m/d/Y", strtotime($cotizacion_recuperada->obtener_invoice_date()));
  renderStatus('Invoice', $statusUrls['invoice'] . $cotizacion_recuperada->obtener_id(), $invoiceDate);
} elseif ($cotizacion_recuperada->obtener_fullfillment()) {
  renderStatus('Fulfillment', $statusUrls['fulfillment'] . $cotizacion_recuperada->obtener_id());
} elseif ($cotizacion_recuperada->obtener_award()) {
  renderStatus('Award', $statusUrls['award'] . $cotizacion_recuperada->obtener_id());
} elseif ($comments === 'No Award - Pricing') {
  renderStatus('No Award - Pricing', null, null, 'fa-times');
} elseif ($comments === 'No Award - Technical') {
  renderStatus('No Award - Technical', null, null, 'fa-times');
} elseif ($cotizacion_recuperada->obtener_status()) {
  if ($cotizacion_recuperada->getSourcesSought()) {
    renderStatus('Sources Sought', null, null, 'fa-search');
  } else {
    renderStatus('Submitted');
  }
} elseif ($cotizacion_recuperada->obtener_completado()) {
  renderStatus('Completed');
}
