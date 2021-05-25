<?php
class Comment{
  private $id;
  private $id_quote;
  private $id_user;
  private $comment;
  private $comment_date;

  public function __construct($id, $id_quote, $id_user, $comment, $comment_date){
    $this-> id = $id;
    $this-> id_quote = $id_quote;
    $this-> id_user = $id_user;
    $this-> comment = $comment;
    $this-> comment_date = $comment_date;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_quote(){
    return $this-> id_quote;
  }

  public function get_id_user(){
    return $this-> id_user;
  }

  public function get_comment(){
    return $this-> comment;
  }

  public function get_comment_date(){
    return $this-> comment_date;
  }
}
?>
