<?php
if(isset($_POST['save_quote_info'])){
  $completed_date = CommentRepository::english_format_to_mysql_date($_POST['completed_date']);
  $expiration_date = CommentRepository::english_format_to_mysql_date($_POST['expiration_date']);
  Database::open_connection();
  $quote = QuoteRepository::get_by_id(Database::get_connection(), $_POST['id_quote']);
  $usuario_antiguo = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote->get_assigned_user());
  $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Database::get_connection(), $_POST['assigned_user']);
  $assigned_user = $usuario->get_id();
  $updated_quote4 = QuoteRepository::update_main_info_quote(Database::get_connection(), $expiration_date, $completed_date, htmlspecialchars($_POST['ship_to']), htmlspecialchars($_POST['address']), $_POST['ship_via'], $_POST['comments'], $assigned_user, $_POST['email_code'], $_POST['type_of_bid'], $_POST['issue_date'], $_POST['end_date'], $_POST['channel'], $_POST['contract_number'], $_POST['id_quote']);
  $quote = QuoteRepository::get_by_id(Database::get_connection(), $_POST['id_quote']);
  AuditTrailRepository::quote_info_events(Database::get_connection(), $_POST['contract_number'], $_POST['contract_number_original'], $_POST['email_code'], $_POST['email_code_original'], $_POST['type_of_bid'], $_POST['type_of_bid_original'], $_POST['issue_date'], $_POST['issue_date_original'], $_POST['end_date'], $_POST['end_date_original'], $_POST['channel'], $_POST['canal_original'], $_POST['assigned_user'], $_POST['designated_user_original'], $_POST['completed_date'], $_POST['completed_date_original'], $_POST['expiration_date'], $_POST['expiration_date_original'], $_POST['comments'], $_POST['comments_original'], $_POST['ship_via'], $_POST['ship_via_original'], $_POST['address'], $_POST['address_original'], $_POST['ship_to'], $_POST['ship_to_original'], $_POST['id_quote']);
  Database::close_connection();
  if ($usuario_antiguo->obtener_nombre_usuario() != $_POST['assigned_user']) {
    $channel = Input::translate_channel($quote->get_channel());
    Redirection::redirect(QUOTES . $channel);
  }
  Redirection::redirect(EDIT_QUOTE . '/' . $_POST['id_quote']);
}
?>
