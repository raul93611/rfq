<?php
Conexion::abrir_conexion();
Report::re_quote_report(Conexion::obtener_conexion(), $_POST['type'], $_POST['quarter'], $_POST['month'], $_POST['year']);
Conexion::cerrar_conexion();
?>
