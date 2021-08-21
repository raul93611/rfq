<?php
Conexion::abrir_conexion();
Report::re_quote_report(Conexion::obtener_conexion(), $_POST['month'], $_POST['year']);
Conexion::cerrar_conexion();
?>
