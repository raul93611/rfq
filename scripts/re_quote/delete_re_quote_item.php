<?php
try {
  // Open a single database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch re-quote item and related data
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id($conexion, $id_re_quote_item);
  $re_quote = ReQuoteRepository::get_re_quote_by_id($conexion, $re_quote_item->get_id_re_quote());
  $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item($conexion, $id_re_quote_item);

  // Delete all subitems if they exist
  if (!empty($re_quote_subitems)) {
    foreach ($re_quote_subitems as $re_quote_subitem) {
      ReQuoteSubitemRepository::delete_re_quote_subitem($conexion, $re_quote_subitem->get_id());
    }
  }

  // Delete the re-quote item
  ReQuoteItemRepository::delete_re_quote_item($conexion, $id_re_quote_item);

  // Log the deletion in the audit trail
  ReQuoteAuditTrailRepository::create_audit_trail_item_deleted(
    $conexion,
    'Item',
    $re_quote_item->get_part_number_project(),
    'Part Number',
    $re_quote_item->get_id_re_quote()
  );

  // Redirect to the appropriate page
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq());
} catch (Exception $e) {
  // Print the error message and terminate script execution
  die('Error: ' . $e->getMessage());
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}
