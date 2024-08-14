<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  $required_fields = [
    'id_subitem', 'id_fulfillment_subitem', 'provider', 'quantity',
    'unit_cost', 'other_cost', 'payment_term', 'comment',
    'transaction_date', 'provider_original', 'quantity_original',
    'unit_cost_original', 'other_cost_original', 'payment_term_original', 'id_rfq'
  ];

  foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
      throw new Exception('Missing required field: ' . $field);
    }
  }

  $id_subitem = intval($_POST['id_subitem']);
  $id_fulfillment_subitem = intval($_POST['id_fulfillment_subitem']);
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
  $id_rfq = intval($_POST['id_rfq']);

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the subitem
  $subitem = RepositorioSubitem::obtener_subitem_por_id($conexion, $id_subitem);

  // Calculate the real cost
  $real_cost = ($quantity * $unit_cost) + $other_cost;

  // Update the fulfillment subitem
  FulfillmentSubitemRepository::update(
    $conexion,
    $id_fulfillment_subitem,
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
  $total_cost = FulfillmentSubitemRepository::get_total_cost($conexion, $id_subitem);

  // Set the fulfillment profit
  RepositorioSubitem::set_fulfillment_profit($conexion, $subitem->obtener_total_price() - $total_cost, $id_subitem);

  // Update the fulfillment profit and total
  RepositorioRfq::set_fulfillment_profit_and_total($conexion, $id_rfq);

  // Create audit trail for subitem edits
  FulfillmentAuditTrailRepository::edit_subitem_events(
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
    $id_fulfillment_subitem,
    $id_rfq
  );

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
