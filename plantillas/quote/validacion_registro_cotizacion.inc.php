<?php
if (isset($_POST['registrar_cotizacion'])) {
  Conexion::abrir_conexion();

  // Retrieve the designated user by username
  $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
  $usuario_designado = $usuario->obtener_id();

  // Validate the quote registration
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
    // Proceed to create and insert the quote
    createAndInsertQuote($validador, $usuario_designado);
  }
}

function createAndInsertQuote($validador, $usuario_designado) {
  Conexion::abrir_conexion();

  $cotizacion = new Rfq(
    '', // Assuming ID will be auto-generated
    $_SESSION['user']->obtener_id(),
    $usuario_designado,
    $_POST['canal'],
    $validador->obtener_email_code(),
    $_POST['type_of_bid'],
    $validador->obtener_issue_date(),
    $validador->obtener_end_date(),
    0, // Assuming these default values are valid
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
    0,
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
    'Net 30',
    null,
    Input::test_input($_POST["reference_url"]) // Sanitize the input
  );

  // Insert quote and retrieve ID
  list($cotizacion_insertada, $id_rfq) = RepositorioRfq::insertar_cotizacion(Conexion::obtener_conexion(), $cotizacion);

  // Log audit trails
  logAuditTrails($id_rfq, $validador);

  Conexion::cerrar_conexion();

  if ($cotizacion_insertada) {
    // Save uploaded files
    saveUploadedFiles($id_rfq);
    Redireccion::redirigir1(CHANNEL . $cotizacion->obtener_canal());
  }
}

function logAuditTrails($id_rfq, $validador) {
  // Log different attributes for auditing
  $attributes = [
    'Designated user' => $_POST['usuario_designado'],
    'Channel' => $_POST['canal'],
    'Code' => $validador->obtener_email_code(),
    'Type of Bid' => $_POST['type_of_bid'],
    'Issue Date' => $validador->obtener_issue_date(),
    'End Date' => $validador->obtener_end_date(),
  ];

  foreach ($attributes as $label => $value) {
    AuditTrailRepository::create_audit_trail_modified(Conexion::obtener_conexion(), $label, $value, '', $id_rfq);
  }
}

function saveUploadedFiles($id_rfq) {
  $directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
  $documentos = $_FILES['documentos'];

  Input::save_files($directorio, $documentos, $id_rfq);
}
