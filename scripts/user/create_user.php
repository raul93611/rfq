<?php
header('Content-Type: application/json');

$errors = null;
Conexion::abrir_conexion();
if (RepositorioUsuario::nombre_usuario_existe(Conexion::obtener_conexion(), $_POST['username'])) {
  $errors = 'Username is already taken';
} else if (RepositorioUsuario::email_existe(Conexion::obtener_conexion(), $_POST['email'])) {
  $errors = 'Email is already taken';
} else {
  $nuevo_usuario = new Usuario('', $_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['nombres'], $_POST['apellidos'], implode(',', $_POST['cargo']), $_POST['email'], 0, '');
  RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $nuevo_usuario);
}
Conexion::cerrar_conexion();

echo json_encode(array(
  'errors' => $errors
));
