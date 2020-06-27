<?php
if(isset($_POST['save_edit_re_quote_subitem_provider'])){
  Conexion::abrir_conexion();
  ReQuoteSubitemProviderRepository::update_re_quote_subitem_provider(Conexion::obtener_conexion(), $_POST['provider'], $_POST['price'], $_POST['id_re_quote_subitem_provider']);
  $re_quote_subitem_provider = ReQuoteSubitemProviderRepository::get_re_quote_subitem_provider_by_id(Conexion::obtener_conexion(), $_POST['id_re_quote_subitem_provider']);
  $re_quote_subitem = ReQuoteSubitemRepository::get_re_quote_subitem_by_id(Conexion::obtener_conexion(), $re_quote_subitem_provider-> get_id_re_quote_subitem());
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $re_quote_subitem-> get_id_re_quote_item());
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item-> get_id_re_quote());
  ReQuoteAuditTrailRepository::edit_provider_subitem_events(Conexion::obtener_conexion(), $_POST['provider'], $_POST['provider_original'], $_POST['price'], $_POST['price_original'], $re_quote_subitem_provider-> get_id_re_quote_subitem(), $re_quote_item-> get_id_re_quote());
  Conexion::cerrar_conexion();
  Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq() . '#subitem' . $re_quote_subitem_provider-> get_id_re_quote_subitem());
}
?>
