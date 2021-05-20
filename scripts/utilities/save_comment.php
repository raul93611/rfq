<?php
session_start();
if(isset($_POST['save_comment'])){
  Database::open_connection();
  $comment = new Comment('', $_POST['id_rfq'], $_SESSION['id_usuario'], $_POST['comment_rfq'], '');
  RepositorioComment::insertar_comment(Database::get_connection(), $comment);
  Database::close_connection();
  Redireccion::redirigir(EDIT_QUOTE . '/' . $_POST['id_rfq']);
}
?>
