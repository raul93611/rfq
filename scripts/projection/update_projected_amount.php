<?php
header('Content-Type: application/json');

try {
  // Validate and retrieve POST parameters
  $projected_amount = isset($_POST['projected_amount']) ? $_POST['projected_amount'] : null;
  $id_monthly_projection = isset($_POST['id_monthly_projection']) ? $_POST['id_monthly_projection'] : null;

  if ($projected_amount === null || $id_monthly_projection === null) {
    throw new InvalidArgumentException('Invalid inputs provided.');
  }

  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Update the monthly projection
  MonthlyProjectionRepository::update($conexion, $projected_amount, $id_monthly_projection);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Prepare and send the success response
  $response = array('response' => 'success');
  echo json_encode($response);
} catch (InvalidArgumentException $e) {
  // Handle validation exceptions
  http_response_code(400);
  $response = array('response' => 'error', 'message' => $e->getMessage());
  echo json_encode($response);
} catch (Exception $e) {
  // Handle general exceptions
  http_response_code(500);
  $response = array('response' => 'error', 'message' => 'An error occurred while processing your request.');
  echo json_encode($response);
} finally {
  // Ensure the database connection is closed if it was opened
  if (class_exists('Conexion') && method_exists('Conexion', 'cerrar_conexion')) {
    Conexion::cerrar_conexion();
  }
}
