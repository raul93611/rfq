<?php
Conexion::abrir_conexion();
RepositorioRfq::delete_quote(Conexion::obtener_conexion(), $cotizacion-> obtener_id());
Conexion::cerrar_conexion();
Redireccion::redirigir(CHANNEL . $cotizacion-> obtener_canal());
?>
