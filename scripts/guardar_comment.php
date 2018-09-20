<?php
session_start();
if(isset($_POST['guardar_comment'])){
  Conexion::abrir_conexion();
  $comment = new Comment('', $_POST['id_rfq'], $_SESSION['id_usuario'], $_POST['comment_rfq'], '');
  RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
}
?>
