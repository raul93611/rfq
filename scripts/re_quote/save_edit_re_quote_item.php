<?php
if(isset($_POST['save_edit_re_quote_item'])){
  Conexion::abrir_conexion();
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $_POST['id_re_quote_item']);
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item-> get_id_re_quote());
  ReQuoteItemRepository::update_re_quote_item(Conexion::obtener_conexion(), $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], $_POST['description'], $_POST['description_project'], $_POST['quantity'], $_POST['comments'], $_POST['website'], $_POST['id_re_quote_item']);
  ReQuoteAuditTrailRepository::edit_item_events(Conexion::obtener_conexion(), $_POST['brand'], $_POST['brand_original'], $_POST['brand_project'], $_POST['brand_project_original'], $_POST['part_number'], $_POST['part_number_original'], $_POST['part_number_project'], $_POST['part_number_project_original'], $_POST['description'], $_POST['description_original'], $_POST['description_project'], $_POST['description_project_original'], $_POST['quantity'], $_POST['quantity_original'], $_POST['comments'], $_POST['comments_original'], $_POST['website'], $_POST['website_original'], $_POST['id_re_quote_item'], $re_quote_item-> get_id_re_quote());
  Conexion::cerrar_conexion();
  Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq() . '#item' . $_POST['id_re_quote_item']);
}
?>
