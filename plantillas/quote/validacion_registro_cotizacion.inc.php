<?php
if (isset($_POST['registrar_cotizacion'])) {
  Conexion::abrir_conexion();
  $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
  $usuario_designado = $usuario->obtener_id();
  $validador = new ValidadorCotizacionRegistro(Conexion::obtener_conexion(), $_POST['email_code'], $_POST['issue_date'], $_POST['end_date'], $_POST['type_of_bid'], $_POST['usuario_designado'], $_POST['canal']);
  Conexion::cerrar_conexion();
  if ($validador->registro_cotizacion_valida()) {
    Conexion::abrir_conexion();
    $cotizacion = new Rfq(
      '',
      $_SESSION['id_usuario'],
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
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      0,
      0,
      '',
      '',
      0,
      0,
      0,
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
      0
    );
    list($cotizacion_insertada, $id_rfq) = RepositorioRfq::insertar_cotizacion(Conexion::obtener_conexion(), $cotizacion);
    Conexion::cerrar_conexion();
    if ($cotizacion_insertada) {
      $directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
      $documentos = $_FILES['documentos']['name'];
      $temp_documents = $_FILES['documentos']['tmp_name'];
      Input::save_files($directorio, $documentos, $temp_documents);
      $canal = Input::translate_channel($cotizacion-> obtener_canal());
      Redireccion::redirigir1(COTIZACIONES . $canal);
    }
  }
}
?>
