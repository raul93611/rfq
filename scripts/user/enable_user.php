<?php
session_start();
Conexion::abrir_conexion();
$usuario_editado = RepositorioUsuario::enable_user(Conexion::obtener_conexion(), $id_usuario);
Conexion::cerrar_conexion();
if($usuario_editado){
  Redireccion::redirigir(USERS);
}
?>
