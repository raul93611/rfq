<?php
header('Content-Type: application/json');

Conexion::abrir_conexion();
$usuario_editado = RepositorioUsuario::enable_user(Conexion::obtener_conexion(), $_POST['id']);
Conexion::cerrar_conexion();
?>
