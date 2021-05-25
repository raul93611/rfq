<?php
session_start();
if(isset($_POST['save_comment'])){
  Database::open_connection();
  $comment = new Comment('', $_POST['id_quote'], $_SESSION['id_user'], $_POST['comment_rfq'], '');
  CommentRepository::insert(Database::get_connection(), $comment);
  Database::close_connection();
  Redirection::redirect(EDIT_QUOTE . '/' . $_POST['id_quote']);
}
?>
