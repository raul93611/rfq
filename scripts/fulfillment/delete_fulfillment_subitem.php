<?php
header('Content-Type: application/json');

if (!isset($_POST['id_fulfillment_subitem'], $_POST['id_subitem'], $_POST['id_rfq'])) {
  echo json_encode(['error' => 'Missing required parameters']);
  exit;
}

$id_fulfillment_subitem = (int)$_POST['id_fulfillment_subitem'];
$id_subitem = (int)$_POST['id_subitem'];
$id_rfq = (int)$_POST['id_rfq'];

try {
  Conexion::abrir_conexion();
  $connection = Conexion::obtener_conexion();

  $fulfillment_subitem = FulfillmentSubitemRepository::get_one($connection, $id_fulfillment_subitem);
  $subitem = RepositorioSubitem::obtener_subitem_por_id($connection, $id_subitem);

  FulfillmentSubitemRepository::delete($connection, $id_fulfillment_subitem);
  $total_cost = FulfillmentSubitemRepository::get_total_cost($connection, $id_subitem);

  $subitem_total_price = $subitem->obtener_total_price();
  $fulfillment_profit = $subitem_total_price - $total_cost;
  RepositorioSubitem::set_fulfillment_profit($connection, $fulfillment_profit, $id_subitem);

  RepositorioRfq::set_fulfillment_profit_and_total($connection, $id_rfq);

  FulfillmentAuditTrailRepository::create_audit_trail_item_deleted(
    $connection,
    $fulfillment_subitem->get_provider(),
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
