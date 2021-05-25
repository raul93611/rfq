<?php
if (isset($_POST['registrar_cotizacion'])) {
  Database::open_connection();
  $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Database::get_connection(), $_POST['assigned_user']);
  $assigned_user = $usuario->get_id();
  $validador = new ValidadorCotizacionRegistro(Database::get_connection(), $_POST['email_code'], $_POST['issue_date'], $_POST['end_date'], $_POST['type_of_bid'], $_POST['assigned_user'], $_POST['channel']);
  Database::close_connection();
  if ($validador->registro_cotizacion_valida()) {
    Database::open_connection();
    $quote = new Quote('', $_SESSION['id_user'], $assigned_user, $_POST['channel'], $validador->get_email_code(), $_POST['type_of_bid'], $validador->get_issue_date(), $validador->get_end_date(), 0, 0, 0, 0, '', 0, '', '', '', '', '', '', '', '', 0, 0, '', '', 0, 0, 0, '', null, null, null, null);
    list($inserted_quote, $id_quote) = QuoteRepository::insert(Database::get_connection(), $quote);
    Database::close_connection();
    if ($inserted_quote) {
      $directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documents/' . $id_quote;
      $documents = $_FILES['documents']['name'];
      $temp_documents = $_FILES['documents']['tmp_name'];
      Input::save_files($directorio, $documents, $temp_documents);
      $channel = Input::translate_channel($quote-> get_channel());
      Redirection::redirect_js(QUOTES . $channel);
    }
  }
}
?>
