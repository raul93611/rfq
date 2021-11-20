<?php
$canal = $cotizacion_recuperada-> obtener_canal();
if($cotizacion_recuperada-> obtener_submitted_invoice()){
  echo '<a class="btn btn-primary" id="go_back" href="' . SUBMITTED_INVOICE_QUOTES . '"><i class="fa fa-reply"></i></a>';
}else if($cotizacion_recuperada-> obtener_invoice()){
  echo '<a class="btn btn-primary" id="go_back" href="' . INVOICE_QUOTES . '"><i class="fa fa-reply"></i></a>';
}else if($cotizacion_recuperada-> obtener_fullfillment()){
  echo '<a class="btn btn-primary" id="go_back" href="' . FULFILLMENT_QUOTES . '"><i class="fa fa-reply"></i></a>';
}else if ($cotizacion_recuperada->obtener_award() && ($cotizacion_recuperada->obtener_comments() == 'No comments' || $cotizacion_recuperada->obtener_comments() == 'Working on it' || $cotizacion_recuperada-> obtener_comments() == 'QuickBooks')) {
  echo '<a class="btn btn-primary" id="go_back" href="' . AWARD . $canal . '"><i class="fa fa-reply"></i></a>';
} else if ($cotizacion_recuperada->obtener_status() && ($cotizacion_recuperada->obtener_comments() == 'No comments' || $cotizacion_recuperada->obtener_comments() == 'Working on it' || $cotizacion_recuperada-> obtener_comments() == 'QuickBooks')) {
  echo '<a class="btn btn-primary" id="go_back" href="' . SUBMITTED . $canal . '"><i class="fa fa-reply"></i></a>';
} else if ($cotizacion_recuperada->obtener_completado() && ($cotizacion_recuperada->obtener_comments() == 'No comments' || $cotizacion_recuperada->obtener_comments() == 'Working on it' || $cotizacion_recuperada-> obtener_comments() == 'QuickBooks')) {
  echo '<a class="btn btn-primary" id="go_back" href="' . COMPLETADOS . $canal . '"><i class="fa fa-reply"></i></a>';
} else if ($cotizacion_recuperada->obtener_comments() == 'No Bid' || $cotizacion_recuperada->obtener_comments() == 'Manufacturer in the Bid' || $cotizacion_recuperada->obtener_comments() == 'Expired due date' || $cotizacion_recuperada->obtener_comments() == 'Supplier did not provide a quote' || $cotizacion_recuperada->obtener_comments() == 'Others') {
  echo '<a class="btn btn-primary" id="go_back" href="' . NO_BID . '"><i class="fa fa-reply"></i></a>';
}else if($cotizacion_recuperada-> obtener_comments() == 'No submitted'){
  echo '<a class="btn btn-primary" id="go_back" href="' . NO_SUBMITTED . '"><i class="fa fa-reply"></i></a>';
}else if(!empty($cotizacion_recuperada-> obtener_canal())){
  echo '<a class="btn btn-primary" id="go_back" href="' . COTIZACIONES . $canal . '"><i class="fa fa-reply"></i></a>';
}
?>
