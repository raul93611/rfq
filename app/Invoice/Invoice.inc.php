<?php
class Invoice{
  private $id;
  private $id_rfq;
  private $name;
  private $created_at;

  public function __construct( $id, $id_rfq, $name, $created_at){
    $this->id = $id;
    $this->id_rfq = $id_rfq;
    $this->name = $name;
    $this->created_at = $created_at;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_id_rfq(){
    return $this->id_rfq;
  }

  public function get_name(){
    return $this->name;
  }

  public function get_created_at(){
    return $this->created_at;
  }
}
?>