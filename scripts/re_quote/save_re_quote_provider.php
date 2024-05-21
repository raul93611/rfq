<?php
if (isset($_POST['save_re_quote_provider'])) {
  $re_quote_provider = new ReQuoteProvider('', $_POST['id_re_quote_item'], $_POST['provider'], $_POST['price']);
  Conexion::abrir_conexion();
  ReQuoteProviderRepository::insert_re_quote_provider(Conexion::obtener_conexion(), $re_quote_provider);
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $_POST['id_re_quote_item']);
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item->get_id_re_quote());
  ReQuoteAuditTrailRepository::create_audit_trail_item_created(Conexion::obtener_conexion(), $_POST['id_re_quote_item'], 'Provider', $_POST['provider'], 'Provider', $re_quote->get_id());
  Conexion::cerrar_conexion();
  Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq());
}
