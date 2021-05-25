<?php
Database::open_connection();
$quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
$items = ItemRepository::get_all_by_id_quote(Database::get_connection(), $quote-> get_id());
foreach ($items as $item) {
  $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item-> get_id());
  if(count($subitems)){
    foreach ($subitems as $subitem) {
      RepositorioSubitem::delete_subitem(Database::get_connection(), $subitem-> get_id());
    }
  }
  ItemRepository::delete_item(Database::get_connection(), $item->get_id());
}
CommentRepository::delete_all_by_quote(Database::get_connection(), $quote-> get_id());
AuditTrailRepository::delete_all_by_id_quote(Database::get_connection(), $quote-> get_id());
$quiz = QuizRepository::get_by_id_quote(Database::get_connection(), $quote-> get_id());
if(!is_null($quiz)){
  QuizRepository::delete_by_id_quote(Database::get_connection(), $quote-> get_id());
}
QuoteRepository::delete(Database::get_connection(), $quote-> get_id());
Database::close_connection();
$channel = Input::translate_channel($quote-> get_channel());
Redirection::redirect(QUOTES . $channel);
?>
