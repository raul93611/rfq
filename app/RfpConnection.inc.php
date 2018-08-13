<?php
class RfpConnection{
  private $id;
  private $id_rfq;
  private $rfp;
  public function __construct($id, $id_rfq, $rfp){
    $this-> id = $id;
    $this-> id_rfq = $id_rfq;
    $this-> rfp = $rfp;
  }

  public function obtener_id(){
    return $this-> id;
  }

  public function obtener_id_rfq(){
    return $this-> id_rfq;
  }

  public function obtener_rfp(){
    return $this-> rfp;
  }
}
?>
