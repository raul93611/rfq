<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
RepositorioRfq::delete_quote(Conexion::obtener_conexion(), $quote->obtener_id());
Conexion::cerrar_conexion();
if ($quote->isNobid()) {
  Redireccion::redirigir(NO_BID);
}
Redireccion::redirigir(CHANNEL . $quote->obtener_canal());
