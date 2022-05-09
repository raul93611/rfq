<?php
Conexion::abrir_conexion();
Report::fulfillment_pending_report(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
?>
