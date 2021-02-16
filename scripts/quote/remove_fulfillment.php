<?php
ConnectionFullFillment::open_connection();
Conexion::abrir_conexion();
RepositorioRfq::remove_fulfillment(Conexion::obtener_conexion(), $id_rfq);
RepositorioRfqFullFillment::remove_fulfillment(ConnectionFullFillment::get_connection(), $id_rfq);
Conexion::cerrar_conexion();
ConnectionFullFillment::close_connection();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq);
?>
