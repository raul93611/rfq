<?php
try {
  // Open a single database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the re-quote provider
  $re_quote_provider = ReQuoteProviderRepository::get_re_quote_provider_by_id($conexion, $id_re_quote_provider);

  // Fetch the re-quote item associated with the provider
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id($conexion, $re_quote_provider->get_id_re_quote_item());

  // Fetch the re-quote associated with the item
  $re_quote = ReQuoteRepository::get_re_quote_by_id($conexion, $re_quote_item->get_id_re_quote());

  // Delete the re-quote provider
  ReQuoteProviderRepository::delete_re_quote_provider($conexion, $id_re_quote_provider);

  // Log the deletion in the audit trail
  ReQuoteAuditTrailRepository::create_audit_trail_item_provider_deleted(
    $conexion,
    $re_quote_provider->get_provider(),
    'Provider',
    $re_quote_item->get_id(),
    $re_quote_item->get_id_re_quote()
  );

  // Redirect to the appropriate page
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq() . '#item' . $re_quote_item->get_id());
} catch (Exception $e) {
  // Print the error message and terminate script execution
  die('Error: ' . $e->getMessage());
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}
