<?php
header('Content-Type: application/json');

try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch all payment terms
  $payment_terms = PaymentTermRepository::get_all($conexion);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Initialize the JSON response array
  $json = [];

  // Iterate through the payment terms and prepare the JSON response
  foreach ($payment_terms as $payment_term) {
    $row = [
      htmlspecialchars($payment_term->get_payment_term(), ENT_QUOTES, 'UTF-8'),
      '<button type="button" data="' . htmlspecialchars($payment_term->get_id(), ENT_QUOTES, 'UTF-8') . '" class="edit_button btn btn-info btn-sm"><i class="fas fa-pen"></i></button>
       <button type="button" data="' . htmlspecialchars($payment_term->get_id(), ENT_QUOTES, 'UTF-8') . '" class="delete_button btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>'
    ];
    $json[] = $row;
  }

  // Encode the response as JSON
  echo json_encode(['data' => $json]);
} catch (Exception $e) {
  // Handle exceptions and prepare an error response
  echo json_encode(['error' => 'Error fetching payment terms: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')]);
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }
}
