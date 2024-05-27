<?php
header('Content-Type: application/json');

// Initialize response array
$response = ['id_rfq' => $_POST['id_rfq'], 'success' => false, 'error' => ''];

// Open the database connection
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

if ($conexion) {
  // Update tracking information
  $success = TrackingRepository::update_tracking(
    $conexion,
    $_POST['quantity'],
    $_POST['carrier'],
    htmlspecialchars($_POST['tracking_number']),
    $_POST['delivery_date'],
    $_POST['due_date'],
    $_POST['signed_by'],
    htmlspecialchars($_POST['comments']),
    $_POST['id_tracking']
  );

  if ($success) {
    $response['success'] = true;
  } else {
    $response['error'] = 'Failed to update tracking information';
  }

  // Close the database connection
  Conexion::cerrar_conexion();
} else {
  $response['error'] = 'Failed to connect to the database';
}

// Output response as JSON
echo json_encode($response);
