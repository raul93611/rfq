<?php
if(isset($_POST['save_edit_re_quote_provider'])){
  Conexion::abrir_conexion();
  ReQuoteProviderRepository::update_re_quote_provider(Conexion::obtener_conexion(), $_POST['provider'], $_POST['price'], $_POST['id_re_quote_provider']);
  $re_quote_provider = ReQuoteProviderRepository::get_re_quote_provider_by_id(Conexion::obtener_conexion(), $_POST['id_re_quote_provider']);
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $re_quote_provider-> get_id_re_quote_item());
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item-> get_id_re_quote());
  Conexion::cerrar_conexion();
  Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq());
}
?>
