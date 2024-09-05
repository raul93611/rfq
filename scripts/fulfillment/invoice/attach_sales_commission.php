<?php
header('Content-Type: application/json');

// Initialize the response array
$response = [];

try {
  // Open database connection
  Conexion::abrir_conexion();
  $connection = Conexion::obtener_conexion();

  // Attach sales commission
  $result = InvoiceRepository::attachSalesCommission($connection, $_POST['id'], $_POST['idRfq']);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Prepare response based on the result
  if ($result) {
    $response = [
      'status' => 'success',
      'message' => 'Sales commission attached successfully.',
      'id_rfq' => $_POST['idRfq']
    ];
  } else {
    throw new Exception('Failed to attach sales commission.');
  }
} catch (Exception $e) {
  // Handle any exceptions and prepare an error response
  $response = [
    'status' => 'error',
    'message' => $e->getMessage()
  ];
}

// Return the JSON response
echo json_encode($response);
exit;
