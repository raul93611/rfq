<?php
if (isset($_POST['save_re_quote_subitem'])) {
  // Sanitize input data
  $id_re_quote_item = filter_var($_POST['id_re_quote_item'], FILTER_SANITIZE_NUMBER_INT);
  $brand = htmlspecialchars($_POST['brand'], ENT_QUOTES, 'UTF-8');
  $brand_project = htmlspecialchars($_POST['brand_project'], ENT_QUOTES, 'UTF-8');
  $part_number = htmlspecialchars($_POST['part_number'], ENT_QUOTES, 'UTF-8');
  $part_number_project = htmlspecialchars($_POST['part_number_project'], ENT_QUOTES, 'UTF-8');
  $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
  $description_project = htmlspecialchars($_POST['description_project'], ENT_QUOTES, 'UTF-8');
  $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
  $comments = htmlspecialchars($_POST['comments'], ENT_QUOTES, 'UTF-8');
  $website = filter_var($_POST['website'], FILTER_SANITIZE_URL);

  // Create a new ReQuoteSubitem instance
  $re_quote_subitem = new ReQuoteSubitem('', $id_re_quote_item, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, 0, 0, $comments, $website, 0);

  try {
    // Open the connection once and reuse it
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Fetch related data
    $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id($conexion, $id_re_quote_item);
    $re_quote = ReQuoteRepository::get_re_quote_by_id($conexion, $re_quote_item->get_id_re_quote());

    // Insert the new subitem
    $id = ReQuoteSubitemRepository::insert_re_quote_subitem($conexion, $re_quote_subitem);

    // Create audit trail
    ReQuoteAuditTrailRepository::create_audit_trail_subitem_created(
      $conexion,
      $id,
      'Subitem',
      $part_number_project,
      'Part Number',
      $re_quote_item->get_id_re_quote()
    );
  } catch (Exception $e) {
    die('Error: ' . $e->getMessage());
  } finally {
    // Ensure the connection is closed
    Conexion::cerrar_conexion();
  }

  // Redirect to the updated quote
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq() . '#subitem' . $id);
}
