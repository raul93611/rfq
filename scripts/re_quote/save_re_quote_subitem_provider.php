<?php
if (isset($_POST['save_re_quote_subitem_provider'])) {
  // Sanitize input data
  $provider = htmlspecialchars($_POST['provider'], ENT_QUOTES, 'UTF-8');
  $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $id_re_quote_subitem = filter_var($_POST['id_re_quote_subitem'], FILTER_SANITIZE_NUMBER_INT);

  // Create a new ReQuoteSubitemProvider instance
  $re_quote_subitem_provider = new ReQuoteSubitemProvider('', $id_re_quote_subitem, $provider, $price);

  try {
    // Open the connection once and reuse it
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Fetch related data
    $re_quote_subitem = ReQuoteSubitemRepository::get_re_quote_subitem_by_id($conexion, $id_re_quote_subitem);
    $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id($conexion, $re_quote_subitem->get_id_re_quote_item());
    $re_quote = ReQuoteRepository::get_re_quote_by_id($conexion, $re_quote_item->get_id_re_quote());

    // Insert the new provider
    ReQuoteSubitemProviderRepository::insert_re_quote_subitem_provider($conexion, $re_quote_subitem_provider);

    // Create audit trail
    ReQuoteAuditTrailRepository::create_audit_trail_subitem_created(
      $conexion,
      $id_re_quote_subitem,
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
