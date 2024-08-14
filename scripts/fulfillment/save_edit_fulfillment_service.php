<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  $required_fields = [
    'id_service', 'id_fulfillment_service', 'provider', 'quantity',
    'unit_cost', 'other_cost', 'payment_term', 'comment',
    'transaction_date', 'provider_original', 'quantity_original',
    'unit_cost_original', 'other_cost_original', 'payment_term_original'
  ];

  foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
      throw new Exception('Missing required field: ' . $field);
    }
  }

  $id_service = intval($_POST['id_service']);
  $id_fulfillment_service = intval($_POST['id_fulfillment_service']);
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

  // Fetch the service
  $service = ServiceRepository::get_service($conexion, $id_service);

  // Calculate the real cost
  $real_cost = ($quantity * $unit_cost) + $other_cost;

  // Update the fulfillment service
  FulfillmentServiceRepository::update(
    $conexion,
    $id_fulfillment_service,
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
  $total_cost = FulfillmentServiceRepository::get_total_cost($conexion, $id_service);

  // Set the fulfillment profit
  ServiceRepository::set_fulfillment_profit($conexion, $service->get_total_price() - $total_cost, $id_service);

  // Update the services fulfillment profit and total
  RepositorioRfq::set_services_fulfillment_profit_and_total($conexion, $service->get_id_rfq());

  // Create audit trail for service edits
  FulfillmentAuditTrailRepository::edit_service_events(
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
    $id_fulfillment_service,
    $service->get_id_rfq()
  );

  // Close the database connection
  Conexion::cerrar_conexion();

  // Return success response
  echo json_encode(['id_rfq' => $service->get_id_rfq()]);
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }

  // Return error response
  die(json_encode(['error' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')]));
}
