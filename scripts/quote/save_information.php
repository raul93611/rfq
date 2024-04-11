<?php
if (isset($_POST['save_information'])) {
  $fecha_completado = !empty($_POST['completed_date']) ? RepositorioComment::english_format_to_mysql_date($_POST['completed_date']) : null;
  $expiration_date = !empty($_POST['expiration_date']) ? RepositorioComment::english_format_to_mysql_date($_POST['expiration_date']) : null;
  Conexion::abrir_conexion();
  $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
  $usuario_designado = $usuario->obtener_id();
  RepositorioRfq::save_information(
    Conexion::obtener_conexion(),
    $expiration_date,
    $fecha_completado,
    htmlspecialchars($_POST['address']),
    $_POST['ship_via'],
    $_POST['type_of_bid'],
    $_POST['issue_date'],
    $_POST['end_date'],
    $usuario_designado,
    $_POST['email_code'],
    $_POST['canal'],
    htmlspecialchars($_POST['ship_to']),
    $_POST['comments'],
    $_POST["bpa"] ?? null,
    $_POST['id_rfq']
  );
  AuditTrailRepository::information_events(
    Conexion::obtener_conexion(), 
    $_POST['type_of_bid'], 
    $_POST['type_of_bid_original'], 
    $_POST['issue_date'], 
    $_POST['issue_date_original'], 
    $_POST['end_date'], 
    $_POST['end_date_original'], 
    $_POST['completed_date'], 
    $_POST['completed_date_original'], 
    $_POST['expiration_date'], 
    $_POST['expiration_date_original'], 
    $_POST['ship_via'], 
    $_POST['ship_via_original'],
    $_POST['email_code'],
    $_POST['email_code_original'],
    $_POST['usuario_designado'],
    $_POST['designated_user_original'],
    $_POST['canal'],
    $_POST['canal_original'],
    $_POST['ship_to'],
    $_POST['ship_to_original'],
    $_POST['comments'],
    $_POST['comments_original'],
    $_POST['id_rfq']
  );
  Conexion::cerrar_conexion();
  Redireccion::redirigir(INFORMATION . $_POST['id_rfq']);
}
