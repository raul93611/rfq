<?php
Conexion::abrir_conexion();
$nombre_usuario = 'raul93611';
$password = password_hash('elogic93611', PASSWORD_DEFAULT);
$nombres = 'leonardo';
$apellidos = 'velasco';
$cargo = 1;
$email = 'asdsadas@asdads';
$usuario = new Usuario('', $nombre_usuario, $password, $nombres, $apellidos, $cargo, $email);
RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);
Conexion::cerrar_conexion();
?>
