<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  if (
    !isset($_POST['id_subitem'], $_POST['quantity'], $_POST['unit_cost'], $_POST['other_cost'], $_POST['provider'], $_POST['payment_term'], $_POST['comment'], $_POST['transaction_date'], $_POST['id_rfq']) ||
    !is_numeric($_POST['quantity']) ||
    !is_numeric($_POST['unit_cost']) ||
    !is_numeric($_POST['other_cost']) ||
    !is_numeric($_POST['id_subitem']) ||
    !is_numeric($_POST['id_rfq'])
  ) {
    throw new Exception('Invalid input data');
  }

  $id_subitem = intval($_POST['id_subitem']);
  $quantity = floatval($_POST['quantity']);
  $unit_cost = floatval($_POST['unit_cost']);
  $other_cost = floatval($_POST['other_cost']);
  $provider = htmlspecialchars($_POST['provider']);
  $payment_term = htmlspecialchars($_POST['payment_term']);
  $comment = htmlspecialchars($_POST['comment']);
  $transaction_date = htmlspecialchars($_POST['transaction_date']);
  $id_rfq = intval($_POST['id_rfq']);
  $invoice = empty($_POST['invoice']) ? null : htmlspecialchars($_POST['invoice']);

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the subitem
  $subitem = RepositorioSubitem::obtener_subitem_por_id($conexion, $id_subitem);

  // Calculate the real cost
  $real_cost = ($quantity * $unit_cost) + $other_cost;

  // Create the fulfillment subitem
  $fulfillment_subitem = new FulfillmentSubitem(
    '',
    $id_subitem,
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

  // Insert the fulfillment subitem
  $id = FulfillmentSubitemRepository::insert($conexion, $fulfillment_subitem);

  // Get the total cost
  $total_cost = FulfillmentSubitemRepository::get_total_cost($conexion, $id_subitem);

  // Set the fulfillment profit
  RepositorioSubitem::set_fulfillment_profit($conexion, $subitem->obtener_total_price() - $total_cost, $id_subitem);

  // Update the fulfillment profit and total
  RepositorioRfq::set_fulfillment_profit_and_total($conexion, $id_rfq);

  // Create an audit trail entry
  FulfillmentAuditTrailRepository::create_audit_trail_subitem_created($conexion, $id, $provider, 'Provider', $id_rfq);

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
