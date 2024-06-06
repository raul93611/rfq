<?php
if (isset($_POST['save_edit_re_quote_subitem'])) {
  try {
    // Open the database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Get the re-quote subitem, item, and re-quote details
    $re_quote_subitem = ReQuoteSubitemRepository::get_re_quote_subitem_by_id($conexion, $_POST['id_re_quote_subitem']);
    $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id($conexion, $re_quote_subitem->get_id_re_quote_item());
    $re_quote = ReQuoteRepository::get_re_quote_by_id($conexion, $re_quote_item->get_id_re_quote());

    // Update the re-quote subitem
    ReQuoteSubitemRepository::update_re_quote_subitem(
      $conexion,
      $_POST['brand'],
      $_POST['brand_project'],
      $_POST['part_number'],
      $_POST['part_number_project'],
      $_POST['description'],
      $_POST['description_project'],
      $_POST['quantity'],
      htmlspecialchars($_POST['comments']),
      $_POST['website'],
      $_POST['id_re_quote_subitem']
    );

    // Log the audit trail
    ReQuoteAuditTrailRepository::edit_subitem_events(
      $conexion,
      $_POST['brand'],
      $_POST['brand_original'],
      $_POST['brand_project'],
      $_POST['brand_project_original'],
      $_POST['part_number'],
      $_POST['part_number_original'],
      $_POST['part_number_project'],
      $_POST['part_number_project_original'],
      $_POST['description'],
      $_POST['description_original'],
      $_POST['description_project'],
      $_POST['description_project_original'],
      $_POST['quantity'],
      $_POST['quantity_original'],
      $_POST['comments'],
      $_POST['comments_original'],
      $_POST['website'],
      $_POST['website_original'],
      $_POST['id_re_quote_subitem'],
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
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq() . '#subitem' . $_POST['id_re_quote_subitem']);
}
