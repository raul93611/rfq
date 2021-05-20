<?php
if (isset($_POST['registrar_cotizacion'])) {
  Database::open_connection();
  $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Database::get_connection(), $_POST['usuario_designado']);
  $usuario_designado = $usuario->obtener_id();
  $validador = new ValidadorCotizacionRegistro(Database::get_connection(), $_POST['email_code'], $_POST['issue_date'], $_POST['end_date'], $_POST['type_of_bid'], $_POST['usuario_designado'], $_POST['canal']);
  Database::close_connection();
  if ($validador->registro_cotizacion_valida()) {
    Database::open_connection();
    $cotizacion = new Rfq('', $_SESSION['id_user'], $usuario_designado, $_POST['canal'], $validador->obtener_email_code(), $_POST['type_of_bid'], $validador->obtener_issue_date(), $validador->obtener_end_date(), 0, 0, 0, 0, '', 0, '', '', '', '', '', '', '', '', 0, 0, '', '', 0, 0, 0, '', null, null, null, null);
    list($cotizacion_insertada, $id_rfq) = RepositorioRfq::insertar_cotizacion(Database::get_connection(), $cotizacion);
    Database::close_connection();
    if ($cotizacion_insertada) {
      $directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documents/' . $id_rfq;
      $documents = $_FILES['documents']['name'];
      $temp_documents = $_FILES['documents']['tmp_name'];
      Input::save_files($directorio, $documents, $temp_documents);
      $canal = Input::translate_channel($cotizacion-> obtener_canal());
      Redireccion::redirigir1(QUOTES . $canal);
    }
  }
}
?>
