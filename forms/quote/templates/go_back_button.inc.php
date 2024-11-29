<?php
$canal = $cotizacion_recuperada->obtener_canal();
$comments = $cotizacion_recuperada->obtener_comments();
$noCommentsStatus = ['No comments', 'Working on it', 'QuickBooks'];
$noBidComments = ['No Bid', 'Manufacturer in the Bid', 'Expired due date', 'Supplier did not provide a quote', 'Others'];

// Define the URL based on the condition
$url = '';

if ($cotizacion_recuperada->getDeleted()) {
  $url = DELETED;
} elseif ($cotizacion_recuperada->obtener_submitted_invoice()) {
  $url = SUBMITTED_INVOICE_QUOTES;
} elseif ($cotizacion_recuperada->obtener_invoice()) {
  $url = INVOICE_QUOTES;
} elseif ($cotizacion_recuperada->obtener_fullfillment()) {
  $url = FULFILLMENT_QUOTES;
} elseif ($cotizacion_recuperada->obtener_award() && in_array($comments, $noCommentsStatus)) {
  $url = AWARD . $canal;
} elseif ($cotizacion_recuperada->obtener_status() && in_array($comments, $noCommentsStatus)) {
  $url = SUBMITTED . $canal;
} elseif ($cotizacion_recuperada->obtener_completado() && in_array($comments, $noCommentsStatus)) {
  $url = COMPLETED . $canal;
} elseif (in_array($comments, $noBidComments)) {
  $url = NO_BID;
} elseif ($comments === 'No submitted') {
  $url = NO_SUBMITTED;
} elseif (!empty($canal)) {
  $url = CHANNEL . $canal;
}

// Output the button if a URL is set
if ($url) {
  echo '<a class="btn btn-primary" id="go_back" href="' . $url . '"><i class="fa fa-reply"></i></a>';
}
