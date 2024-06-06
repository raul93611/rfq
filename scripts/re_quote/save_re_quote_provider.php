<?php
if (isset($_POST['save_re_quote_provider'])) {
  // Sanitize the input data
  $provider = htmlspecialchars($_POST['provider'], ENT_QUOTES, 'UTF-8');
  $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $id_re_quote_item = filter_var($_POST['id_re_quote_item'], FILTER_SANITIZE_NUMBER_INT);

  $re_quote_provider = new ReQuoteProvider('', $id_re_quote_item, $provider, $price);

  try {
    // Open the connection once and reuse it
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Insert the new provider
    ReQuoteProviderRepository::insert_re_quote_provider($conexion, $re_quote_provider);

    // Fetch related data
    $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id($conexion, $id_re_quote_item);
    $re_quote = ReQuoteRepository::get_re_quote_by_id($conexion, $re_quote_item->get_id_re_quote());

    // Create audit trail
    ReQuoteAuditTrailRepository::create_audit_trail_item_created(
      $conexion,
      $id_re_quote_item,
      'Provider',
      $provider,
      'Provider',
      $re_quote->get_id()
    );
  } catch (Exception $e) {
    die('Error: ' . $e->getMessage());
  } finally {
    // Ensure the connection is closed
    Conexion::cerrar_conexion();
  }

  // Redirect to the updated quote
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq());
}
