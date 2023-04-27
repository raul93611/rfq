<?php
if (isset($_POST['save_checklist'])) {
  $file_document = is_null($_POST['file_document']) ? [] : $_POST['file_document'];
  $accounting = is_null($_POST['accounting']) ? [] : $_POST['accounting'];
  $estimated_delivery_date = !empty($_POST['estimated_delivery_date']) ? RepositorioComment::english_format_to_mysql_date($_POST['estimated_delivery_date']) : null;
  Conexion::abrir_conexion();
  $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
  $usuario_designado = $usuario->obtener_id();
  RepositorioRfq::save_checklist(
    Conexion::obtener_conexion(),
    htmlspecialchars($_POST['ship_to']),
    $usuario_designado,
    $_POST['email_code'],
    $_POST['canal'],
    $_POST['contract_number'],
    $_POST['city'],
    $_POST['zip_code'],
    $_POST['state'],
    $_POST['client'],
    $_POST['set_side'],
    $_POST['poc'],
    $_POST['co'],
    $estimated_delivery_date,
    implode('|', $file_document),
    implode('|', $accounting),
    $_POST['shipping_address'],
    $_POST['special_requirements'],
    $_POST['gsa'],
    $_POST['client_payment_terms'],
    $_POST['id_rfq']
  );
  AuditTrailRepository::checklist_events(
    Conexion::obtener_conexion(),
    $_POST['contract_number'],
    $_POST['contract_number_original'],
    $_POST['email_code'],
    $_POST['email_code_original'],
    $_POST['canal'],
    $_POST['canal_original'],
    $_POST['usuario_designado'],
    $_POST['designated_user_original'],
    $_POST['ship_to'],
    $_POST['ship_to_original'],
    $_POST['id_rfq']
  );
  Conexion::cerrar_conexion();
  Redireccion::redirigir(CHECKLIST . $_POST['id_rfq']);
}
