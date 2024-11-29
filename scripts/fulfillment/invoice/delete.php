<?php
header('Content-Type: application/json');

try {
  // Open the connection
  Conexion::abrir_conexion();

  // Execute the delete operation
  $isDeleted = InvoiceRepository::delete(Conexion::obtener_conexion(), $_POST["id"]);

  // Check if the delete operation was successful
  if ($isDeleted) {
    $response = ['response' => 'success'];
  } else {
    http_response_code(400);
    $response = ['response' => 'error', 'message' => 'Failed to delete invoice.'];
  }
} catch (Exception $ex) {
  // Handle any unexpected errors
  http_response_code(500);
  $response = ['response' => 'error', 'message' => 'An error occurred: ' . $ex->getMessage()];
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}

// Return the response as JSON
echo json_encode($response);
