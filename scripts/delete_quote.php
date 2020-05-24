<?php
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
if(!$cotizacion-> obtener_rfp()){
  $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $cotizacion-> obtener_id());
  foreach ($items as $item) {
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    if(count($subitems)){
      foreach ($subitems as $subitem) {
        RepositorioSubitem::delete_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
      }
    }
    RepositorioItem::delete_item(Conexion::obtener_conexion(), $item->obtener_id());
  }
  RepositorioComment::delete_all_comments(Conexion::obtener_conexion(), $cotizacion-> obtener_id());
  $cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id_rfq(Conexion::obtener_conexion(), $cotizacion-> obtener_id());
  if(!is_null($cuestionario)){
    RepositorioCuestionario::delete_cuestionario_por_id_rfq(Conexion::obtener_conexion(), $cotizacion-> obtener_id());
  }
  RepositorioRfq::delete_quote(Conexion::obtener_conexion(), $cotizacion-> obtener_id());
}
Conexion::cerrar_conexion();
$canal = Input::translate_channel($cotizacion-> obtener_canal());
Redireccion::redirigir(COTIZACIONES . $canal);
?>
