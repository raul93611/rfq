<?php
Conexion::abrir_conexion();
Report::profit_report(Conexion::obtener_conexion(), $_POST['month'], $_POST['year']);
Conexion::cerrar_conexion();
?>
