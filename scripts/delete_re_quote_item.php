<?php
Conexion::abrir_conexion();
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $id_re_quote_item);
$re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item-> get_id_re_quote());
$re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $id_re_quote_item);
if(count($re_quote_subitems)){
  foreach ($re_quote_subitems as $key => $re_quote_subitem) {
    ReQuoteSubitemRepository::delete_re_quote_subitem(Conexion::obtener_conexion(), $re_quote_subitem-> get_id());
  }
}
ReQuoteItemRepository::delete_re_quote_item(Conexion::obtener_conexion(), $id_re_quote_item);
Conexion::cerrar_conexion();
Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq());
?>
