<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
list($array_total_cost_quote, $array_total_price_quote, $array_total_profit_quote, $array_total_cost_requote, $array_total_price_requote, $array_total_profit_requote, $array_total_cost_fulfillment, $array_total_price_fulfillment, $array_total_profit_fulfillment) = Report::profit_chart(Conexion::obtener_conexion(), 'yearly', $_POST['quarter'], $_POST['month'], $_POST['year']);
Conexion::cerrar_conexion();

echo json_encode(array(
  'array_total_cost_quote' => $array_total_cost_quote,
  'array_total_price_quote' => $array_total_price_quote,
  'array_total_profit_quote' => $array_total_profit_quote,
  'array_total_cost_requote' => $array_total_cost_requote,
  'array_total_price_requote' => $array_total_price_requote,
  'array_total_profit_requote' => $array_total_profit_requote,
  'array_total_cost_fulfillment' => $array_total_cost_fulfillment,
  'array_total_price_fulfillment' => $array_total_price_fulfillment,
  'array_total_profit_fulfillment' => $array_total_profit_fulfillment
));
?>