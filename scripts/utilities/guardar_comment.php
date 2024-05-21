<?php
if (isset($_POST['guardar_comment'])) {
  Conexion::abrir_conexion();
  $comment = new Comment('', $_POST['id_rfq'], $_SESSION['user']->obtener_id(), $_POST['comment_rfq'], '');
  RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
  Conexion::cerrar_conexion();
  if ($_POST['place'] == 'quote') {
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
  } else {
    Redireccion::redirigir(FULFILLMENT . '/' . $_POST['id_rfq']);
  }
}
