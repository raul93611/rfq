<?php
header('Content-Type: application/json');

$errors = null;
Conexion::abrir_conexion();
$user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $_POST["id_user"]);
if($user->obtener_nombre_usuario() == $_POST['username'] && !RepositorioUsuario::usernameExistMoreThan2(Conexion::obtener_conexion(), $_POST["username"])){
  $edited_user = RepositorioUsuario::edit_user(Conexion::obtener_conexion(), $_POST['username'], $_POST['nombres'], $_POST['apellidos'], implode(',', $_POST['cargo']), $_POST['email'], $_POST['id_user']);
} else {
  $errors = 'Username is already taken';
}
Conexion::cerrar_conexion();
echo json_encode(array(
  'errors' => $errors
));
