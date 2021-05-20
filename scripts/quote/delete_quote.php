<?php
Database::open_connection();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Database::get_connection(), $id_rfq);
$items = RepositorioItem::obtener_items_por_id_rfq(Database::get_connection(), $cotizacion-> obtener_id());
foreach ($items as $item) {
  $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item-> obtener_id());
  if(count($subitems)){
    foreach ($subitems as $subitem) {
      RepositorioSubitem::delete_subitem(Database::get_connection(), $subitem-> obtener_id());
    }
  }
  RepositorioItem::delete_item(Database::get_connection(), $item->obtener_id());
}
RepositorioComment::delete_all_comments(Database::get_connection(), $cotizacion-> obtener_id());
AuditTrailRepository::delete_audit_trails(Database::get_connection(), $cotizacion-> obtener_id());
$cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id_rfq(Database::get_connection(), $cotizacion-> obtener_id());
if(!is_null($cuestionario)){
  RepositorioCuestionario::delete_cuestionario_por_id_rfq(Database::get_connection(), $cotizacion-> obtener_id());
}
RepositorioRfq::delete_quote(Database::get_connection(), $cotizacion-> obtener_id());
Database::close_connection();
$canal = Input::translate_channel($cotizacion-> obtener_canal());
Redirection::redirect(QUOTES . $canal);
?>
