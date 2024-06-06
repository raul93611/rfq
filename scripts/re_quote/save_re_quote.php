<?php
if (isset($_POST['save_re_quote'])) {
  // Sanitize input data
  $id_re_quote = filter_var($_POST['id_re_quote'], FILTER_SANITIZE_NUMBER_INT);
  $payment_terms = filter_input(INPUT_POST, 'payment_terms', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $total_cost = filter_input(INPUT_POST, 'total_cost', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $shipping = filter_input(INPUT_POST, 'shipping', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $shipping_cost = filter_input(INPUT_POST, 'shipping_cost', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $services_payment_term = filter_input(INPUT_POST, 'services_payment_term', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  try {
    // Open database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Fetch existing re-quote and RFQ
    $re_quote = ReQuoteRepository::get_re_quote_by_id($conexion, $id_re_quote);
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id($conexion, $re_quote->get_id_rfq());

    // Update the re-quote
    ReQuoteRepository::update_re_quote(
      $conexion,
      $payment_terms,
      $total_cost,
      $shipping,
      $shipping_cost,
      $services_payment_term,
      $id_re_quote
    );

    // Calculate items with cost calculation (CC)
    ReQuoteServiceRepository::calc_items_with_CC(
      $conexion,
      $services_payment_term,
      $id_re_quote
    );

    // Record audit trail events
    ReQuoteAuditTrailRepository::items_table_events(
      $conexion,
      $payment_terms,
      $_POST['payment_terms_original'] ?? null,
      $shipping,
      $_POST['shipping_original'] ?? null,
      $shipping_cost,
      $_POST['shipping_cost_original'] ?? null,
      $id_re_quote
    );
  } catch (Exception $e) {
    // Handle exceptions gracefully
    die('Error: ' . $e->getMessage());
  } finally {
    // Ensure the connection is closed
    Conexion::cerrar_conexion();
  }

  // Redirect to the updated quote page
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq());
}
