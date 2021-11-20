<?php
if(isset($_POST['save_re_quote_subitem_provider'])){
  $re_quote_subitem_provider = new ReQuoteSubitemProvider('', $_POST['id_re_quote_subitem'], $_POST['provider'], $_POST['price']);
  Conexion::abrir_conexion();
  $re_quote_subitem = ReQuoteSubitemRepository::get_re_quote_subitem_by_id(Conexion::obtener_conexion(), $_POST['id_re_quote_subitem']);
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $re_quote_subitem-> get_id_re_quote_item());
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item-> get_id_re_quote());
  ReQuoteSubitemProviderRepository::insert_re_quote_subitem_provider(Conexion::obtener_conexion(), $re_quote_subitem_provider);
  ReQuoteAuditTrailRepository::create_audit_trail_subitem_created(Conexion::obtener_conexion(), $_POST['id_re_quote_subitem'], 'Provider', $_POST['provider'], 'Provider', $re_quote-> get_id());
  Conexion::cerrar_conexion();
  Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq());
}
?>
