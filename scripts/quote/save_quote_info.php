<?php
if(isset($_POST['save_quote_info'])){
  $fecha_completado = RepositorioComment::english_format_to_mysql_date($_POST['completed_date']);
  $expiration_date = RepositorioComment::english_format_to_mysql_date($_POST['expiration_date']);
  Conexion::abrir_conexion();
  $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $usuario_antiguo = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_usuario_designado());
  $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
  $usuario_designado = $usuario->obtener_id();
  $cotizacion_editada4 = RepositorioRfq::actualizar_rfq_inicio(Conexion::obtener_conexion(), $expiration_date, $fecha_completado, htmlspecialchars($_POST['ship_to']), htmlspecialchars($_POST['address']), $_POST['ship_via'], $_POST['comments'], $usuario_designado, $_POST['email_code'], $_POST['type_of_bid'], $_POST['issue_date'], $_POST['end_date'], $_POST['canal'], $_POST['contract_number'], $_POST['id_rfq']);
  $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  AuditTrailRepository::quote_info_events(Conexion::obtener_conexion(), $_POST['contract_number'], $_POST['contract_number_original'], $_POST['email_code'], $_POST['email_code_original'], $_POST['type_of_bid'], $_POST['type_of_bid_original'], $_POST['issue_date'], $_POST['issue_date_original'], $_POST['end_date'], $_POST['end_date_original'], $_POST['canal'], $_POST['canal_original'], $_POST['usuario_designado'], $_POST['designated_user_original'], $_POST['completed_date'], $_POST['completed_date_original'], $_POST['expiration_date'], $_POST['expiration_date_original'], $_POST['comments'], $_POST['comments_original'], $_POST['ship_via'], $_POST['ship_via_original'], $_POST['address'], $_POST['address_original'], $_POST['ship_to'], $_POST['ship_to_original'], $_POST['id_rfq']);
  Conexion::cerrar_conexion();
  if ($usuario_antiguo->obtener_nombre_usuario() != $_POST['usuario_designado']) {
    Redireccion::redirigir(COTIZACIONES . $cotizacion_recuperada->obtener_canal());
  }
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
}
?>
