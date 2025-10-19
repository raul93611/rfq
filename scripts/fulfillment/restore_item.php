<?php
header('Content-Type: application/json');

try {
  // Validate required parameters
  if (!isset($_POST['item_type'], $_POST['object_data'], $_POST['parent_id'], $_POST['rfq_id'])) {
    throw new Exception('Missing required parameters');
  }

  $item_type = $_POST['item_type'];
  $object_data = $_POST['object_data'];
  $parent_id = intval($_POST['parent_id']);
  $rfq_id = intval($_POST['rfq_id']);

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Unserialize the object
  $restored_object = unserialize(base64_decode($object_data));

  // Based on item type, restore the appropriate item
  switch ($item_type) {
    case 'item':
      // Restore fulfillment item
      $id = FulfillmentItemRepository::insert($conexion, $restored_object);

      // Get the total cost
      $total_cost = FulfillmentItemRepository::get_total_cost($conexion, $parent_id);

      // Fetch the item and set fulfillment profit
      $item = RepositorioItem::obtener_item_por_id($conexion, $parent_id);
      RepositorioItem::set_fulfillment_profit($conexion, $item->obtener_total_price() - $total_cost, $parent_id);

      // Update RFQ fulfillment profit and total
      RepositorioRfq::set_fulfillment_profit_and_total($conexion, $rfq_id);

      // Create audit trail for restoration
      FulfillmentAuditTrailRepository::create_audit_trail_item_created($conexion, $id, $restored_object->get_provider(), 'Provider', $rfq_id);
      break;

    case 'service':
      // Restore fulfillment service
      $id = FulfillmentServiceRepository::insert($conexion, $restored_object);

      // Get the total cost
      $total_cost = FulfillmentServiceRepository::get_total_cost($conexion, $parent_id);

      // Fetch the service and set fulfillment profit
      $service = ServiceRepository::get_service($conexion, $parent_id);
      ServiceRepository::set_fulfillment_profit($conexion, $service->get_total_price() - $total_cost, $parent_id);

      // Update RFQ services fulfillment profit and total
      RepositorioRfq::set_services_fulfillment_profit_and_total($conexion, $rfq_id);

      // Create audit trail for restoration
      FulfillmentAuditTrailRepository::create_audit_trail_service_created($conexion, $id, $restored_object->provider, 'Provider', $rfq_id);
      break;

    case 'subitem':
      // Restore fulfillment subitem
      $id = FulfillmentSubitemRepository::insert($conexion, $restored_object);

      // Get the total cost
      $total_cost = FulfillmentSubitemRepository::get_total_cost($conexion, $parent_id);

      // Fetch the subitem and set fulfillment profit
      $subitem = RepositorioSubitem::obtener_subitem_por_id($conexion, $parent_id);
      RepositorioSubitem::set_fulfillment_profit($conexion, $subitem->obtener_total_price() - $total_cost, $parent_id);

      // Update RFQ fulfillment profit and total
      RepositorioRfq::set_fulfillment_profit_and_total($conexion, $rfq_id);

      // Create audit trail for restoration
      FulfillmentAuditTrailRepository::create_audit_trail_subitem_created($conexion, $id, $restored_object->provider, 'Provider', $rfq_id);
      break;

    default:
      throw new Exception('Unknown item type: ' . $item_type);
  }

  // Close the database connection
  Conexion::cerrar_conexion();

  // Return success response with RFQ ID
  echo json_encode(['success' => true, 'id_rfq' => $rfq_id]);
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }

  // Return error response
  die(json_encode(['success' => false, 'error' => $e->getMessage()]));
}