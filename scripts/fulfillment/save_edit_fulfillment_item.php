<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  $required_fields = [
    'id_item', 'id_fulfillment_item', 'provider', 'quantity',
    'unit_cost', 'other_cost', 'payment_term', 'comment',
    'transaction_date', 'provider_original', 'quantity_original',
    'unit_cost_original', 'other_cost_original', 'payment_term_original'
  ];

  foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
      throw new Exception('Missing required field: ' . $field);
    }
  }

  $id_item = intval($_POST['id_item']);
  $id_fulfillment_item = intval($_POST['id_fulfillment_item']);
  $quantity = floatval($_POST['quantity']);
  $unit_cost = floatval($_POST['unit_cost']);
  $other_cost = floatval($_POST['other_cost']);
  $payment_term = htmlspecialchars($_POST['payment_term']);
  $comment = htmlspecialchars($_POST['comment']);
  $transaction_date = htmlspecialchars($_POST['transaction_date']);
  $invoice = empty($_POST["invoice"]) ? null : htmlspecialchars($_POST["invoice"]);
  $provider = htmlspecialchars($_POST['provider']);
  $provider_original = htmlspecialchars($_POST['provider_original']);
  $quantity_original = floatval($_POST['quantity_original']);
  $unit_cost_original = floatval($_POST['unit_cost_original']);
  $other_cost_original = floatval($_POST['other_cost_original']);
  $payment_term_original = htmlspecialchars($_POST['payment_term_original']);

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the item
  $item = RepositorioItem::obtener_item_por_id($conexion, $id_item);

  // Calculate the real cost
  $real_cost = ($quantity * $unit_cost) + $other_cost;

  // Update the fulfillment item
  FulfillmentItemRepository::update(
    $conexion,
    $id_fulfillment_item,
    $provider,
    $quantity,
    $unit_cost,
    $other_cost,
    $real_cost,
    $payment_term,
    $comment,
    $invoice,
    $transaction_date
  );

  // Get the total cost
  $total_cost = FulfillmentItemRepository::get_total_cost($conexion, $id_item);

  // Set the fulfillment profit
  RepositorioItem::set_fulfillment_profit($conexion, $item->obtener_total_price() - $total_cost, $id_item);

  // Update the fulfillment profit and total
  RepositorioRfq::set_fulfillment_profit_and_total($conexion, $item->obtener_id_rfq());

  // Create audit trail for item edits
  FulfillmentAuditTrailRepository::edit_item_events(
    $conexion,
    $provider,
    $provider_original,
    $quantity,
    $quantity_original,
    $unit_cost,
    $unit_cost_original,
    $other_cost,
    $other_cost_original,
    $payment_term,
    $payment_term_original,
    $id_fulfillment_item,
    $item->obtener_id_rfq()
  );

  // Close the database connection
  Conexion::cerrar_conexion();

  // Return success response
  echo json_encode(['id_rfq' => $item->obtener_id_rfq()]);
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }

  // Return error response
  die(json_encode(['error' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')]));
}
