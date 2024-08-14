<?php
header('Content-Type: application/json');

$response = ['result' => false]; // Default response

try {
  // Validate and sanitize input
  if (!isset($_POST['id_payment_term']) || !is_numeric($_POST['id_payment_term'])) {
    throw new Exception("Invalid payment term ID");
  }
  if (!isset($_POST['name']) || empty(trim($_POST['name']))) {
    throw new Exception("Invalid input data");
  }

  // Validate the payment term
  $payment_term = PaymentTermRepository::validate_payment_term($_POST);
  if (!$payment_term) {
    throw new Exception("Payment term validation failed");
  }

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Update the payment term
  $update_result = PaymentTermRepository::update($conexion, $payment_term, intval($_POST['id_payment_term']));

  // Close the database connection
  Conexion::cerrar_conexion();

  // Prepare success result
  $response['result'] = $update_result;
} catch (Exception $e) {
  // Handle exceptions and prepare error result
  $response['error'] = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');

  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }
}

// Output the JSON response
echo json_encode($response);
