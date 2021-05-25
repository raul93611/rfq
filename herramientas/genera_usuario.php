<?php
Database::open_connection();
$nombre_usuario = 'raul93611';
$password = password_hash('elogic93611', PASSWORD_DEFAULT);
$nombres = 'leonardo';
$apellidos = 'velasco';
$role = 1;
$email = 'asdsadas@asdads';
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $role, $email, 1, '');
RepositorioUsuario::insertar_usuario(Database::get_connection(), $usuario);

$nombre_usuario = 'pepe1234';
$password = password_hash('123456', PASSWORD_DEFAULT);
$nombres = 'jose';
$apellidos = 'peres';
$role = 4;
$email = 'jose@peres';
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $role, $email, 1, '');
RepositorioUsuario::insertar_usuario(Database::get_connection(), $usuario);

$nombre_usuario = 'pedro1234';
$password = password_hash('123456', PASSWORD_DEFAULT);
$nombres = 'pedro';
$apellidos = 'puma';
$role = 4;
$email = 'pedro@puma';
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $role, $email, 1, '');
RepositorioUsuario::insertar_usuario(Database::get_connection(), $usuario);

$nombre_usuario = 'pablo1234';
$password = password_hash('123456', PASSWORD_DEFAULT);
$nombres = 'pablo';
$apellidos = 'paredes';
$role = 3;
$email = 'pablo@paredes';
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $role, $email, 1, '');
RepositorioUsuario::insertar_usuario(Database::get_connection(), $usuario);
Database::close_connection();
?>
