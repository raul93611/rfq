<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$quotes_amounts = Report::getAnnualQuotesAmountsByMonth(Conexion::obtener_conexion(), $_POST['year'], 'invoice');
$re_quotes_amounts = Report::getAnnualReQuotesAmountsByMonth(Conexion::obtener_conexion(), $_POST['year'], 'invoice');
$fulfillment_amounts = Report::getAnnualFulfillmentAmountsByMonth(Conexion::obtener_conexion(), $_POST['year'], 'invoice');
Conexion::cerrar_conexion();

echo json_encode(array(
  'quote_amounts' => $quotes_amounts,
  're_quotes_amounts' => $re_quotes_amounts,
  'fulfillment_amounts' => $fulfillment_amounts
));
