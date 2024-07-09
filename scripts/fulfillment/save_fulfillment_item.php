<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  if (
    !isset($_POST['id_item'], $_POST['quantity'], $_POST['unit_cost'], $_POST['other_cost'], $_POST['provider'], $_POST['payment_term'], $_POST['comment'], $_POST['transaction_date']) ||
    !is_numeric($_POST['quantity']) ||
    !is_numeric($_POST['unit_cost']) ||
    !is_numeric($_POST['other_cost']) ||
    !is_numeric($_POST['id_item'])
  ) {
    throw new Exception('Invalid input data');
  }

  $id_item = intval($_POST['id_item']);
  $quantity = floatval($_POST['quantity']);
  $unit_cost = floatval($_POST['unit_cost']);
  $other_cost = floatval($_POST['other_cost']);
  $provider = htmlspecialchars($_POST['provider']);
  $payment_term = htmlspecialchars($_POST['payment_term']);
  $comment = htmlspecialchars($_POST['comment']);
  $transaction_date = htmlspecialchars($_POST['transaction_date']);
  $invoice = empty($_POST['invoice']) ? null : htmlspecialchars($_POST['invoice']);

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the item
  $item = RepositorioItem::obtener_item_por_id($conexion, $id_item);

  // Calculate the real cost
  $real_cost = ($quantity * $unit_cost) + $other_cost;

  // Create the fulfillment item
  $fulfillment_item = new FulfillmentItem(
    '',
    $id_item,
    $provider,
    $quantity,
    $unit_cost,
    $other_cost,
    $real_cost,
    $payment_term,
    $comment,
    0,
    '',
    $invoice,
    $transaction_date
  );

  // Insert the fulfillment item
  $id = FulfillmentItemRepository::insert($conexion, $fulfillment_item);

  // Get the total cost
  $total_cost = FulfillmentItemRepository::get_total_cost($conexion, $id_item);

  // Set the fulfillment profit
  RepositorioItem::set_fulfillment_profit($conexion, $item->obtener_total_price() - $total_cost, $id_item);

  // Update the fulfillment profit and total
  RepositorioRfq::set_fulfillment_profit_and_total($conexion, $item->obtener_id_rfq());

  // Create an audit trail entry
  FulfillmentAuditTrailRepository::create_audit_trail_item_created($conexion, $id, $provider, 'Provider', $item->obtener_id_rfq());

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
