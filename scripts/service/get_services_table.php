<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  exit;
}

header('Content-Type: text/html; charset=utf-8');

Conexion::abrir_conexion();
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
ServiceRepository::display_services(Conexion::obtener_conexion(), $cotizacion_recuperada);
Conexion::cerrar_conexion();
