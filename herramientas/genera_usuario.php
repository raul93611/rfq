<?php
Conexion::abrir_conexion();
$nombre_usuario = 'raul93611';
$password = password_hash('123456', PASSWORD_DEFAULT);
$nombres = 'leonardo';
$apellidos = 'velasco';
$cargo = 1;
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $cargo);
RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);
Conexion::cerrar_conexion();
?>
