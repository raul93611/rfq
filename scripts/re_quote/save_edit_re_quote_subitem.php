<?php
if (isset($_POST['save_edit_re_quote_subitem'])) {
  Conexion::abrir_conexion();
  $re_quote_subitem = ReQuoteSubitemRepository::get_re_quote_subitem_by_id(Conexion::obtener_conexion(), $_POST['id_re_quote_subitem']);
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $re_quote_subitem->get_id_re_quote_item());
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item->get_id_re_quote());
  ReQuoteSubitemRepository::update_re_quote_subitem(Conexion::obtener_conexion(), $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], $_POST['description'], $_POST['description_project'], $_POST['quantity'], htmlspecialchars($_POST['comments']), $_POST['website'], $_POST['id_re_quote_subitem']);
  ReQuoteAuditTrailRepository::edit_subitem_events(Conexion::obtener_conexion(), $_POST['brand'], $_POST['brand_original'], $_POST['brand_project'], $_POST['brand_project_original'], $_POST['part_number'], $_POST['part_number_original'], $_POST['part_number_project'], $_POST['part_number_project_original'], $_POST['description'], $_POST['description_original'], $_POST['description_project'], $_POST['description_project_original'], $_POST['quantity'], $_POST['quantity_original'], $_POST['comments'], $_POST['comments_original'], $_POST['website'], $_POST['website_original'], $_POST['id_re_quote_subitem'], $re_quote_item->get_id_re_quote());
  Conexion::cerrar_conexion();
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq() . '#subitem' . $_POST['id_re_quote_subitem']);
}
