<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  if (!isset($_POST['id_fulfillment_service']) || !isset($_POST['id_service'])) {
    throw new Exception('Missing required POST parameters.');
  }

  $id_fulfillment_service = intval($_POST['id_fulfillment_service']);
  $id_service = intval($_POST['id_service']);

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Get fulfillment service and service
  $fulfillment_service = FulfillmentServiceRepository::get_one($conexion, $id_fulfillment_service);
  $service = ServiceRepository::get_service($conexion, $id_service);

  // Mark the fulfillment service as reviewed
  FulfillmentServiceRepository::mark_as_reviewed($conexion, $id_fulfillment_service);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Return success response
  echo json_encode(['id_rfq' => $service->get_id_rfq()]);
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }

  // Return error response
  die(json_encode(['error' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')]));
}
