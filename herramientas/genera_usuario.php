<?php
Conexion::abrir_conexion();
$nombre_usuario = 'raul93611';
$password = password_hash('elogic93611', PASSWORD_DEFAULT);
$nombres = 'leonardo';
$apellidos = 'velasco';
$cargo = 1;
$email = 'asdsadas@asdads';
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $cargo, $email, 1);
RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);

$nombre_usuario = 'pepe1234';
$password = password_hash('123456', PASSWORD_DEFAULT);
$nombres = 'jose';
$apellidos = 'peres';
$cargo = 4;
$email = 'jose@peres';
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $cargo, $email, 1);
RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);

$nombre_usuario = 'pedro1234';
$password = password_hash('123456', PASSWORD_DEFAULT);
$nombres = 'pedro';
$apellidos = 'puma';
$cargo = 4;
$email = 'pedro@puma';
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $cargo, $email, 1);
RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);

$nombre_usuario = 'ppablo1234';
$password = password_hash('123456', PASSWORD_DEFAULT);
$nombres = 'pablo';
$apellidos = 'paredes';
$cargo = 3;
$email = 'pablo@paredes';
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $cargo, $email, 1);
RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);
Conexion::cerrar_conexion();
?>
