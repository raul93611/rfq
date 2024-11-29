<?php
if (isset($_POST['save_information'])) {
  try {
    // Open database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Prepare data
    $fecha_completado = !empty($_POST['completed_date']) ? $_POST['completed_date'] : null;
    $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;
    $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario($conexion, $_POST['usuario_designado']);
    $usuario_designado = $usuario->obtener_id();

    // Save information
    RepositorioRfq::save_information(
      $conexion,
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
      $_POST["reference_url"],
      $_POST['id_rfq']
    );

    // Log information events
    AuditTrailRepository::information_events(
      $conexion,
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

    // Close database connection
    Conexion::cerrar_conexion();

    // Redirect to information page
    Redireccion::redirigir(INFORMATION . $_POST['id_rfq']);
  } catch (Exception $e) {
    // Ensure the connection is closed in case of an error
    if (isset($conexion)) {
      Conexion::cerrar_conexion();
    }

    // Handle the exception (logging, user feedback, etc.)
    error_log('Error saving information: ' . $e->getMessage());
    // Optionally, redirect to an error page or display an error message
    Redireccion::redirigir(ERROR);
  }
}
