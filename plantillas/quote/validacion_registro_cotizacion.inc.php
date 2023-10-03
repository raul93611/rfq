<?php
if (isset($_POST['registrar_cotizacion'])) {
  Conexion::abrir_conexion();
  $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
  $usuario_designado = $usuario->obtener_id();
  $validador = new ValidadorCotizacionRegistro(
    Conexion::obtener_conexion(), 
    $_POST['email_code'], 
    $_POST['issue_date'], 
    $_POST['end_date'], 
    $_POST['type_of_bid'], 
    $_POST['usuario_designado'], 
    $_POST['canal']
  );
  Conexion::cerrar_conexion();
  if ($validador->registro_cotizacion_valida()) {
    Conexion::abrir_conexion();
    $cotizacion = new Rfq(
      '',
      $_SESSION['user']-> obtener_id(),
      $usuario_designado,
      $_POST['canal'],
      $validador->obtener_email_code(),
      $_POST['type_of_bid'],
      $validador->obtener_issue_date(),
      $validador->obtener_end_date(),
      0,
      0,
      0,
      0,
      'No comments',
      0,
      null,
      null,
      null,
      'Net 30',
      '',
      '',
      null,
      '',
      0,
      0,
      0,
      '',
      0,
      0,
      null,
      '',
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      0,
      null,
      0,
      0,
      null,
      null,
      null,
      null,
      null,
      'Net 30',
      null,
      null,
      null,
      null,
      0,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      'Net 30'
    );
    list($cotizacion_insertada, $id_rfq) = RepositorioRfq::insertar_cotizacion(Conexion::obtener_conexion(), $cotizacion);
    AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Created', $id_rfq);
    AuditTrailRepository::create_audit_trail_modified(Conexion::obtener_conexion(), 'Designated user', $_POST['usuario_designado'], '', $id_rfq);
    AuditTrailRepository::create_audit_trail_modified(Conexion::obtener_conexion(), 'Channel', $_POST['canal'], '', $id_rfq);
    AuditTrailRepository::create_audit_trail_modified(Conexion::obtener_conexion(), 'Code', $validador->obtener_email_code(), '', $id_rfq);
    AuditTrailRepository::create_audit_trail_modified(Conexion::obtener_conexion(), 'Type of Bid', $_POST['type_of_bid'], '', $id_rfq);
    AuditTrailRepository::create_audit_trail_modified(Conexion::obtener_conexion(), 'Issue Date', $validador->obtener_issue_date(), '', $id_rfq);
    AuditTrailRepository::create_audit_trail_modified(Conexion::obtener_conexion(), 'End Date', $validador->obtener_end_date(), '', $id_rfq);
    Conexion::cerrar_conexion();
    if ($cotizacion_insertada) {
      $directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
      $documentos = $_FILES['documentos'];
      Input::save_files($directorio, $documentos, $id_rfq);
      Redireccion::redirigir1(CHANNEL . $cotizacion-> obtener_canal());
    }
  }
}
