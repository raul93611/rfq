<?php
Conexion::abrir_conexion();
Report::award_report(Conexion::obtener_conexion(), $_POST['type'], $_POST['quarter'], $_POST['month'], $_POST['year']);
Conexion::cerrar_conexion();
?>