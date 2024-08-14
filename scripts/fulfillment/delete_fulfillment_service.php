<?php
header('Content-Type: application/json');

if (!isset($_POST['id_fulfillment_service'], $_POST['id_service'])) {
  echo json_encode(['error' => 'Missing required parameters']);
  exit;
}

$id_fulfillment_service = (int)$_POST['id_fulfillment_service'];
$id_service = (int)$_POST['id_service'];

try {
  Conexion::abrir_conexion();
  $connection = Conexion::obtener_conexion();

  $fulfillment_service = FulfillmentServiceRepository::get_one($connection, $id_fulfillment_service);
  $service = ServiceRepository::get_service($connection, $id_service);

  if (!$fulfillment_service || !$service) {
    throw new Exception('Service or Fulfillment Service not found');
  }

  FulfillmentServiceRepository::delete($connection, $id_fulfillment_service);
  $total_cost = FulfillmentServiceRepository::get_total_cost($connection, $id_service);

  $service_total_price = $service->get_total_price();
  $fulfillment_profit = $service_total_price - $total_cost;
  ServiceRepository::set_fulfillment_profit($connection, $fulfillment_profit, $id_service);

  $id_rfq = $service->get_id_rfq();
  RepositorioRfq::set_services_fulfillment_profit_and_total($connection, $id_rfq);

  FulfillmentAuditTrailRepository::create_audit_trail_item_deleted(
    $connection,
    $fulfillment_service->get_provider(),
    'Provider',
    $id_rfq
  );

  Conexion::cerrar_conexion();

  echo json_encode(['id_rfq' => $id_rfq]);
} catch (Exception $e) {
  Conexion::cerrar_conexion();
  echo json_encode(['error' => $e->getMessage()]);
  exit;
}
