<?php
if (isset($_POST['save_checklist'])) {
  try {
    // Open database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Prepare data
    $file_document = $_POST['file_document'] ?? [];
    $accounting = $_POST['accounting'] ?? [];
    $estimated_delivery_date = !empty($_POST['estimated_delivery_date']) ? $_POST['estimated_delivery_date'] : null;
    $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario($conexion, $_POST['usuario_designado']);
    $usuario_designado = $usuario->obtener_id();

    // Save checklist
    RepositorioRfq::save_checklist(
      $conexion,
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
      $_POST['bpa'] ?? null,
      $_POST['id_rfq']
    );

    // Log checklist events
    AuditTrailRepository::checklist_events(
      $conexion,
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

    // Close database connection
    Conexion::cerrar_conexion();

    // Redirect to checklist
    Redireccion::redirigir(CHECKLIST . $_POST['id_rfq']);
  } catch (Exception $e) {
    // Ensure the connection is closed in case of an error
    if (isset($conexion)) {
      Conexion::cerrar_conexion();
    }

    // Handle the exception (logging, user feedback, etc.)
    error_log('Error saving checklist: ' . $e->getMessage());
    // Optionally, redirect to an error page or display an error message
    Redireccion::redirigir(ERROR);
  }
}
