<?php
if(isset($_POST['send'])){

  function sa($longitud){
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numero_caracteres = strlen($caracteres);
    $string_aleatorio = '';

    for($i = 0; $i < $longitud; $i++){
      $string_aleatorio .= $caracteres[rand(0, $numero_caracteres - 1)];
    }
    return $string_aleatorio;
  }
  Database::open_connection();
  $email_existe = RepositorioUsuario::email_existe(Database::get_connection(), $_POST['email']);
  Database::close_connection();
  if($email_existe){
    Database::open_connection();
    $usuario = RepositorioUsuario::obtener_usuario_por_email(Database::get_connection(), $_POST['email']);
    $string_aleatorio = sa(10);
    $url_secreta = hash('sha256', $string_aleatorio . $usuario-> obtener_nombre_usuario());
    RepositorioUsuario::guardar_url_secreta(Database::get_connection(), $usuario-> obtener_id(), $url_secreta);
    Database::close_connection();
    $to = $usuario-> obtener_email();
    $subject = 'Restart your password';
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: E-logic <elogic@e-logic.us>\r\n";
    $message = '
    <html>
    <body>
    <h3>Restart your password:</h3>
    <p><a href="http://www.elogicportal.com/rfq/restart_password/' . $url_secreta . '">Restart your password</a></p>
    </body>
    </html>
    ';
    mail($to, $subject, $message, $headers);
  }
}
?>
