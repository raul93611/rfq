<?php
if (isset($_POST['save_edit_re_quote_provider'])) {
  try {
    // Open the database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Update the re-quote provider
    ReQuoteProviderRepository::update_re_quote_provider(
      $conexion,
      $_POST['provider'],
      $_POST['price'],
      $_POST['id_re_quote_provider']
    );

    // Get the updated re-quote provider details
    $re_quote_provider = ReQuoteProviderRepository::get_re_quote_provider_by_id($conexion, $_POST['id_re_quote_provider']);

    // Get the re-quote item and re-quote details
    $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id($conexion, $re_quote_provider->get_id_re_quote_item());
    $re_quote = ReQuoteRepository::get_re_quote_by_id($conexion, $re_quote_item->get_id_re_quote());

    // Log the audit trail
    ReQuoteAuditTrailRepository::edit_provider_item_events(
      $conexion,
      $_POST['provider'],
      $_POST['provider_original'],
      $_POST['price'],
      $_POST['price_original'],
      $re_quote_provider->get_id_re_quote_item(),
      $re_quote_item->get_id_re_quote()
    );
  } catch (Exception $e) {
    // Handle any exceptions by displaying an error message and terminating the script
    die('Error: ' . $e->getMessage());
  } finally {
    // Ensure the connection is closed
    Conexion::cerrar_conexion();
  }

  // Redirect to the specified RE_QUOTE page
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq() . '#item' . $re_quote_provider->get_id_re_quote_item());
}
