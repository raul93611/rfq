<?php
header('Content-Type: application/json');

$errors = null;
Conexion::abrir_conexion();
RepositorioUsuario::update_password(Conexion::obtener_conexion(), password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['id_user']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'errors' => $errors
));
