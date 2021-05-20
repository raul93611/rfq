<?php
session_start();
Database::open_connection();
$usuario_editado = RepositorioUsuario::enable_user(Database::get_connection(), $id_usuario);
Database::close_connection();
if($usuario_editado){
  Redireccion::redirigir(PROFILE);
}
?>
