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
      $subject = 'New Comment: Proposal - ' . $cotizacion-> obtener_id();
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
      </body>
      </html>
      ';
      mail($to, $subject, $message, $headers);
    }
  }

  if($cotizacion-> obtener_rfp()){
    Connection::open_connection();
    $user_0 = UserRepository::get_user_level_0(Connection::get_connection());
    $comment = new RfpComment('', $cotizacion-> obtener_rfp(), $user_0-> get_id(), '', $_POST['comment_rfq']);
    CommentRepository::insert_comment(Connection::get_connection(), $comment);
    $rfq_users = UserRepository::get_users_3_4(Connection::get_connection());
    Connection::close_connection();
    foreach ($rfq_users as $rfq_user) {
      $to = $rfq_user-> get_email();
      $subject = 'New comment: Project - ' . $cotizacion-> obtener_rfp();
      $headers = "MIME-Version: 1.0\r\n";
      $headers .= "Content-type: text/html; charset=UTF-8\r\n";
      $headers .= "From:" .  $_SESSION['nombre_usuario']  . " <elogic@e-logic.us>\r\n";
      $message = '
      <html>
      <body>
      <h3>Details:</h3>
      <p>' . nl2br($_POST['comment_rfq']) . '</p>
      <h5>Quote:</h5>
      <p><a href="http://www.elogicportal.com/rfp/profile/info_project_and_services/' . $cotizacion-> obtener_rfp() . '">' . $cotizacion-> obtener_rfp() . '</a></p>
      </body>
      </html>
      ';
      mail($to, $subject, $message, $headers);
    }
  }
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
}
?>
