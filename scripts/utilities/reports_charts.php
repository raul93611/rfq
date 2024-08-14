<?php
header('Content-Type: application/json');
// Initialize response array
$response = [
  'quote_amounts' => [],
  're_quotes_amounts' => [],
  'fulfillment_amounts' => [],
];

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch data
  $quotes_amounts = Report::getAnnualQuotesAmountsByMonth($conexion, $_POST['year'], 'invoice');
  $re_quotes_amounts = Report::getAnnualReQuotesAmountsByMonth($conexion, $_POST['year'], 'invoice');
  $fulfillment_amounts = Report::getAnnualFulfillmentAmountsByMonth($conexion, $_POST['year'], 'invoice');

  // Assign fetched data to response
  $response['quote_amounts'] = $quotes_amounts;
  $response['re_quotes_amounts'] = $re_quotes_amounts;
  $response['fulfillment_amounts'] = $fulfillment_amounts;
} catch (Exception $e) {
  // Handle exceptions and include error message in the response
  $response['error'] = 'Error fetching data: ' . $e->getMessage();
} finally {
  // Ensure the database connection is closed
  Conexion::cerrar_conexion();
}

// Output the response as JSON
echo json_encode($response);
