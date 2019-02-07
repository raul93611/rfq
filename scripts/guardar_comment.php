<?php
session_start();
if(isset($_POST['guardar_comment'])){
  Conexion::abrir_conexion();
  $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $comment = new Comment('', $_POST['id_rfq'], $_SESSION['id_usuario'], $_POST['comment_rfq'], '');
  RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
  Conexion::cerrar_conexion();
  if($cotizacion-> obtener_fullfillment()){
    ConnectionFullFillment::open_connection();
    $comment = new CommentRfqFullFillment('', $_POST['id_rfq'], $_SESSION['nombre_usuario'], $_POST['comment_rfq'], '');
    RepositorioRfqFullFillmentComment::insertar_comment(ConnectionFullFillment::get_connection(), $comment);
    $fullfillment_users = UserFullFillmentRepository::get_all_fullfillment_users(ConnectionFullFillment::get_connection());
    ConnectionFullFillment::close_connection();
    foreach ($fullfillment_users as $fullfillment_user) {
      $to = $fullfillment_user-> get_email();
      $subject = 'New quote: proposal ' . $cotizacion-> obtener_id();
      $headers = "MIME-Version: 1.0\r\n";
      $headers .= "Content-type: text/html; charset=UTF-8\r\n";
      $headers .= "From:" .  $_SESSION['nombre_usuario']  . " <elogic@e-logic.us>\r\n";
      $message = '
      <html>
      <body>
      <h3>Details:</h3>
      <p>' . nl2br($_POST['comment_rfq']) . '</p>
      <h5>Quote:</h5>
      <p><a href="http://www.elogicportal.com/fullfillment/profile/edit_quote/' . $cotizacion-> obtener_id() . '">' . $cotizacion-> obtener_id() . '</a></p>
      <h5>Comment:</h5>
      <p>New quote in fullfillment.</p>
      </body>
      </html>
      ';
      mail($to, $subject, $message, $headers);
    }
  }
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
}
?>
