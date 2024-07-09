<?php
header('Content-Type: application/json');

// Ensure POST variables are set and not empty
if (!isset($_POST['id_fulfillment_item']) || !isset($_POST['id_item'])) {
  http_response_code(400); // Bad request
  echo json_encode(array('error' => 'Missing required parameters'));
  exit;
}

$id_fulfillment_item = $_POST['id_fulfillment_item'];
$id_item = $_POST['id_item'];

// Establish database connection
Conexion::abrir_conexion();

try {
  // Retrieve fulfillment item and item from repositories
  $fulfillment_item = FulfillmentItemRepository::get_one(Conexion::obtener_conexion(), $id_fulfillment_item);
  $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);

  // Mark fulfillment item as reviewed
  FulfillmentItemRepository::mark_as_reviewed(Conexion::obtener_conexion(), $id_fulfillment_item);

  // Close database connection
  Conexion::cerrar_conexion();

  // Return JSON response with success message
  echo json_encode(array(
    'success' => true,
    'message' => 'Fulfillment item reviewed successfully',
    'id_rfq' => $item->obtener_id_rfq()
  ));
} catch (Exception $e) {
  // Handle any exceptions or errors
  http_response_code(500); // Internal Server Error
  echo json_encode(array('error' => 'Failed to review fulfillment item: ' . $e->getMessage()));
}
