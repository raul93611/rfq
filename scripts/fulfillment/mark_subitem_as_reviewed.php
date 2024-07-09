<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  if (!isset($_POST['id_fulfillment_subitem']) || !isset($_POST['id_rfq'])) {
    throw new Exception('Missing required POST parameters.');
  }

  $id_fulfillment_subitem = intval($_POST['id_fulfillment_subitem']);
  $id_rfq = intval($_POST['id_rfq']);

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Mark the fulfillment subitem as reviewed
  FulfillmentSubitemRepository::mark_as_reviewed($conexion, $id_fulfillment_subitem);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Return success response
  echo json_encode(['id_rfq' => $id_rfq]);
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }

  // Return error response
  die(json_encode(['error' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')]));
}
