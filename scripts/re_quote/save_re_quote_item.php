<?php
if (isset($_POST['save_re_quote_item'])) {
  // Create a new ReQuoteItem instance
  $re_quote_item = new ReQuoteItem(
    '',
    $_POST['id_re_quote'],
    $_POST['brand'],
    $_POST['brand_project'],
    $_POST['part_number'],
    $_POST['part_number_project'],
    $_POST['description'],
    $_POST['description_project'],
    $_POST['quantity'],
    0,
    0,
    htmlspecialchars($_POST['comments']),
    $_POST['website'],
    0
  );

  try {
    // Open the database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Get the re-quote details
    $re_quote = ReQuoteRepository::get_re_quote_by_id($conexion, $_POST['id_re_quote']);

    // Insert the new re-quote item and get its ID
    $id = ReQuoteItemRepository::insert_re_quote_item($conexion, $re_quote_item);

    // Log the creation of the new item in the audit trail
    ReQuoteAuditTrailRepository::create_audit_trail_item_created(
      $conexion,
      $id,
      'Item',
      $_POST['part_number_project'],
      'Part Number',
      $_POST['id_re_quote']
    );
  } catch (Exception $e) {
    // Handle any exceptions by displaying an error message and terminating the script
    die('Error: ' . $e->getMessage());
  } finally {
    // Ensure the connection is closed
    Conexion::cerrar_conexion();
  }

  // Redirect to the specified RE_QUOTE page
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq() . '#item' . $id);
}
