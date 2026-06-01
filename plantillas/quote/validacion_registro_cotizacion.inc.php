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

  $cotizacion = new Rfq([
    'id' => '',
    'id_usuario' => $_SESSION['user']->obtener_id(),
    'usuario_designado' => $usuario_designado,
    'canal' => $_POST['canal'],
    'email_code' => $validador->obtener_email_code(),
    'type_of_bid' => $_POST['type_of_bid'],
    'issue_date' => $validador->obtener_issue_date(),
    'end_date' => $validador->obtener_end_date(),
    'status' => 0,
    'completado' => 0,
    'total_cost' => 0,
    'total_price' => 0,
    'comments' => 'No comments',
    'award' => 0,
    'fecha_completado' => null,
    'fecha_submitted' => null,
    'fecha_award' => null,
    'payment_terms' => 'Net 30',
    'address' => '',
    'ship_to' => '',
    'expiration_date' => null,
    'ship_via' => '',
    'taxes' => 0,
    'profit' => 0,
    'additional' => 0,
    'shipping' => '',
    'shipping_cost' => 0,
    'fullfillment' => 0,
    'fulfillment_date' => null,
    'contract_number' => '',
    'fulfillment_profit' => null,
    'services_fulfillment_profit' => null,
    'total_fulfillment' => null,
    'total_services_fulfillment' => null,
    'invoice' => 0,
    'invoice_date' => null,
    'multi_year_project' => null,
    'submitted_invoice' => 0,
    'submitted_invoice_date' => null,
    'fulfillment_pending' => 0,
    'fulfillment_shipping_cost' => 0,
    'fulfillment_shipping' => null,
    'type_of_contract' => null,
    'net30_fulfillment' => null,
    'sales_commission' => null,
    'sales_commission_comment' => null,
    'services_payment_term' => 'Net 30',
    'city' => null,
    'zip_code' => null,
    'state' => null,
    'client' => null,
    'deleted' => 0,
    'set_side' => null,
    'poc' => null,
    'co' => null,
    'estimated_delivery_date' => null,
    'shipping_address' => null,
    'special_requirements' => null,
    'file_document' => null,
    'accounting' => null,
    'gsa' => null,
    'client_payment_terms' => 'Net 30',
    'net30_fulfillment_services' => null,
    'bpa' => null,
    'reference_url' => Input::test_input($_POST["reference_url"]),
    'priority' => isset($_POST['priority_level']) ? $_POST['priority_level'] : null,
    'name' => !empty($_POST['name']) ? htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8') : null,
    'site_visit'       => isset($_POST['site_visit'])  && $_POST['site_visit']  !== '' ? (int)$_POST['site_visit']  : null,
    'resumes'          => isset($_POST['resumes'])     && $_POST['resumes']     !== '' ? (int)$_POST['resumes']     : null,
    'qa_deadline'      => !empty($_POST['qa_deadline'])      ? $_POST['qa_deadline']      : null,
    'internal_due_date'=> !empty($_POST['internal_due_date'])? $_POST['internal_due_date']: null,
    'qa'               => isset($_POST['qa'])             && $_POST['qa']             !== '' ? (int)$_POST['qa']             : null,
  ]);

  // Insert quote and retrieve ID
  list($cotizacion_insertada, $id_rfq) = RepositorioRfq::insertar_cotizacion(Conexion::obtener_conexion(), $cotizacion);

  // Log audit trails
  logAuditTrails($id_rfq, $validador);

  // Auto-sync to SharePoint sheet for qualifying bid types
  $syncable_bid_types = ['Audio Visual', 'Services'];
  if ($cotizacion_insertada && in_array($_POST['type_of_bid'], $syncable_bid_types)) {
    try {
      $designatedUsername = $_POST['usuario_designado'];
      $insertedQuote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
      $sheetRow = SheetSyncService::appendRow($insertedQuote, $designatedUsername);
      SheetSyncRepository::updateSyncStatus(Conexion::obtener_conexion(), $id_rfq, 'synced', $sheetRow);
    } catch (Exception $syncEx) {
      SheetSyncRepository::updateSyncStatus(Conexion::obtener_conexion(), $id_rfq, 'failed');
      error_log('Sheet sync error on create: ' . $syncEx->getMessage());
    }
  }

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
