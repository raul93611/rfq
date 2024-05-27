<?php
header('Content-Type: application/json');

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Update the tracking subitem
  $update_success = TrackingSubitemRepository::update_tracking_subitem(
    $conexion,
    $_POST['quantity'],
    $_POST['carrier'],
    htmlspecialchars($_POST['tracking_number']),
    $_POST['delivery_date'],
    $_POST['due_date'],
    $_POST['signed_by'],
    htmlspecialchars($_POST['comments']),
    $_POST['id_tracking_subitem']
  );

  // Close the connection
  Conexion::cerrar_conexion();

  // Return the result as JSON
  echo json_encode([
    'success' => $update_success,
    'id_rfq' => htmlspecialchars($_POST['id_rfq'])
  ]);
} catch (Exception $e) {
  // Close the connection if an error occurs
  Conexion::cerrar_conexion();

  // Return the error message as JSON
  echo json_encode([
    'error' => 'An error occurred: ' . $e->getMessage()
  ]);
}
