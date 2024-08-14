<?php
header('Content-Type: application/json');

try {
  // Validate input
  if (!isset($_POST['id_rfq']) || !is_numeric($_POST['id_rfq'])) {
    throw new Exception('Invalid or missing RFQ ID');
  }
  if (!isset($_POST['value'])) {
    throw new Exception('Missing value');
  }

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Save net 30 services and update fulfillment profit and total
  RepositorioRfq::save_net_30_services($conexion, $_POST['id_rfq'], $_POST['value']);
  RepositorioRfq::set_services_fulfillment_profit_and_total($conexion, $_POST['id_rfq']);

  // Close database connection
  Conexion::cerrar_conexion();

  // Return success response
  echo json_encode([
    'id_rfq' => $_POST['id_rfq'],
  ]);
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }

  // Return error response
  die(json_encode([
    'error' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
  ]));
}
