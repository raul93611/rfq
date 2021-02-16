<?php
class ReQuoteAuditTrail{
  private $id;
  private $id_re_quote;
  private $username;
  private $audit_trail;
  private $created_date;

  public function __construct($id, $id_re_quote, $username, $audit_trail, $created_date){
    $this-> id = $id;
    $this-> id_re_quote = $id_re_quote;
    $this-> username = $username;
    $this-> audit_trail = $audit_trail;
    $this-> created_date = $created_date;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_re_quote(){
    return $this-> id_re_quote;
  }

  public function get_username(){
    return $this-> username;
  }

  public function get_audit_trail(){
    return $this-> audit_trail;
  }

  public function get_created_date(){
    return $this-> created_date;
  }
}
?>
