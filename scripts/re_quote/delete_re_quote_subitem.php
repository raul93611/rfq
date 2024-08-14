<?php
try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the re-quote subitem
  $re_quote_subitem = ReQuoteSubitemRepository::get_re_quote_subitem_by_id($conexion, $id_re_quote_subitem);

  // Fetch the re-quote item associated with the subitem
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id($conexion, $re_quote_subitem->get_id_re_quote_item());

  // Fetch the re-quote associated with the item
  $re_quote = ReQuoteRepository::get_re_quote_by_id($conexion, $re_quote_item->get_id_re_quote());

  // Delete the re-quote subitem
  ReQuoteSubitemRepository::delete_re_quote_subitem($conexion, $id_re_quote_subitem);

  // Redirect to the appropriate page
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq());
} catch (Exception $e) {
  // Print the error message and terminate script execution
  die('Error: ' . $e->getMessage());
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}
