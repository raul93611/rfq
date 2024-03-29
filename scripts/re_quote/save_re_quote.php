<?php
if(isset($_POST['save_re_quote'])){
  Conexion::abrir_conexion();
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $_POST['id_re_quote']);
  $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $re_quote-> get_id_rfq());
  ReQuoteRepository::update_re_quote(Conexion::obtener_conexion(), $_POST['payment_terms'], $_POST['total_cost'], $_POST['shipping'], $_POST['shipping_cost'], $_POST['services_payment_term'], $_POST['id_re_quote']);
  ReQuoteServiceRepository::calc_items_with_CC(Conexion::obtener_conexion(), $_POST['services_payment_term'], $_POST['id_re_quote']);
  ReQuoteAuditTrailRepository::items_table_events(Conexion::obtener_conexion(), $_POST['payment_terms'], $_POST['payment_terms_original'], $_POST['shipping'], $_POST['shipping_original'], $_POST['shipping_cost'], $_POST['shipping_cost_original'], $_POST['id_re_quote']);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq());
}
?>
