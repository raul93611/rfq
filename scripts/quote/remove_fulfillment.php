<?php
Conexion::abrir_conexion();
RepositorioRfq::remove_fulfillment(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq);
?>
