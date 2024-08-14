<?php
header('Content-Type: application/json');

// Open the database connection
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

// Fetch the required data
$quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($conexion, $id_rfq);
$total_services = ServiceRepository::get_total($conexion, $id_rfq);

// Close the database connection
Conexion::cerrar_conexion();

// Prepare the response data
$response = [
  'general' => [
    'quote' => [
      $quote->obtener_quote_total_price(),
      $quote->obtener_quote_total_cost(),
      $quote->obtener_quote_profit()
    ],
    'reQuote' => [
      null,
      $quote->obtener_re_quote_total_cost(),
      $quote->obtener_re_quote_profit()
    ],
    'fulfillment' => [
      null,
      $quote->obtener_fulfillment_total_cost(),
      $quote->obtener_real_fulfillment_profit()
    ],
  ],
  'rfq' => [
    'quote' => [
      $quote->obtener_total_price(),
      $quote->obtener_total_cost(),
      $quote->obtener_total_price() - $quote->obtener_total_cost()
    ],
    'reQuote' => [
      null,
      $re_quote->get_total_cost(),
      $quote->obtener_total_price() - $re_quote->get_total_cost()
    ],
    'fulfillment' => [
      null,
      $quote->obtener_total_fulfillment(),
      $quote->obtener_total_price() - $quote->obtener_total_fulfillment()
    ],
  ],
  'rfp' => [
    $total_services,
    $quote->obtener_total_services_fulfillment(),
    $total_services - $quote->obtener_total_services_fulfillment()
  ],
];

// Output the JSON response
echo json_encode($response);
