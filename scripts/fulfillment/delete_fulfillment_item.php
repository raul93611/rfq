<?php
header('Content-Type: application/json');

if (!isset($_POST['id_fulfillment_item'], $_POST['id_item'])) {
  echo json_encode(['error' => 'Missing required parameters']);
  exit;
}

$id_fulfillment_item = (int)$_POST['id_fulfillment_item'];
$id_item = (int)$_POST['id_item'];

try {
  Conexion::abrir_conexion();
  $connection = Conexion::obtener_conexion();

  $fulfillment_item = FulfillmentItemRepository::get_one($connection, $id_fulfillment_item);
  $item = RepositorioItem::obtener_item_por_id($connection, $id_item);

  if (!$fulfillment_item || !$item) {
    throw new Exception('Item or Fulfillment Item not found');
  }

  FulfillmentItemRepository::delete($connection, $id_fulfillment_item);
  $total_cost = FulfillmentItemRepository::get_total_cost($connection, $id_item);

  $item_total_price = $item->obtener_total_price();
  $fulfillment_profit = $item_total_price - $total_cost;
  RepositorioItem::set_fulfillment_profit($connection, $fulfillment_profit, $id_item);

  $id_rfq = $item->obtener_id_rfq();
  RepositorioRfq::set_fulfillment_profit_and_total($connection, $id_rfq);

  FulfillmentAuditTrailRepository::create_audit_trail_item_deleted(
    $connection,
    $fulfillment_item->get_provider(),
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
