<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  if (isset($_POST['id_payment_term']) && is_numeric($_POST['id_payment_term'])) {
    $id_payment_term = intval($_POST['id_payment_term']);
  } else {
    throw new Exception("Invalid payment term ID");
  }

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Attempt to delete payment term
  $delete_success = PaymentTermRepository::delete($conexion, $id_payment_term);

  // Check if delete was successful
  if (!$delete_success) {
    throw new Exception("Failed to delete payment term");
  }

  // Prepare response
  $response = array('result' => true);

  // Close database connection
  Conexion::cerrar_conexion();
} catch (Exception $e) {
  // Handle exceptions and prepare error response
  $response = array(
    'result' => false,
    'message' => $e->getMessage()
  );

  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }
}

// Output the JSON response
echo json_encode($response);
