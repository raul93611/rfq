<?php
Conexion::abrir_conexion();
$service = ServiceRepository::get_service(Conexion::obtener_conexion(), $id_service);
ServiceRepository::delete_service(Conexion::obtener_conexion(), $id_service);
Conexion::cerrar_conexion();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $service-> get_id_rfq() . '#services_table');
?>
