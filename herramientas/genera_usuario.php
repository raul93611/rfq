<?php
Conexion::abrir_conexion();
$nombre_usuario = 'raul93611';
$password = password_hash('elogic93611', PASSWORD_DEFAULT);
$nombres = 'leonardo';
$apellidos = 'velasco';
$cargo = 1;
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $cargo);
RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);
$nombre_usuario = 'pepe1234';
$password = password_hash('123456', PASSWORD_DEFAULT);
$nombres = 'jose';
$apellidos = 'perez';
$cargo = 2;
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $cargo);
RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);
$nombre_usuario = 'pedro1234';
$password = password_hash('123456', PASSWORD_DEFAULT);
$nombres = 'pedro';
$apellidos = 'lopez';
$cargo = 3;
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $cargo);
RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);
$nombre_usuario = 'pablo1234';
$password = password_hash('123456', PASSWORD_DEFAULT);
$nombres = 'pablo';
$apellidos = 'zeballos';
$cargo = 4;
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $cargo);
RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);
Conexion::cerrar_conexion();
?>
