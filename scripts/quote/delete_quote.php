<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
RepositorioRfq::delete_quote(Conexion::obtener_conexion(), $quote->obtener_id());
AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Deleted', $id_rfq);
Conexion::cerrar_conexion();
if ($quote->isNobid()) {
  Redireccion::redirigir(NO_BID);
}
if ($quote->isNotSubmitted()) {
  Redireccion::redirigir(NO_SUBMITTED);
}
if ($quote->isCancelled()) {
  Redireccion::redirigir(CANCELLED);
}
Redireccion::redirigir(CHANNEL . $quote->obtener_canal());
