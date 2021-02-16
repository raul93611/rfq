<?php
class Comment{
  private $id;
  private $id_rfq;
  private $id_usuario;
  private $comment;
  private $fecha_comment;

  public function __construct($id, $id_rfq, $id_usuario, $comment, $fecha_comment){
    $this-> id = $id;
    $this-> id_rfq = $id_rfq;
    $this-> id_usuario = $id_usuario;
    $this-> comment = $comment;
    $this-> fecha_comment = $fecha_comment;
  }

  public function obtener_id(){
    return $this-> id;
  }

  public function obtener_id_rfq(){
    return $this-> id_rfq;
  }

  public function obtener_id_usuario(){
    return $this-> id_usuario;
  }

  public function obtener_comment(){
    return $this-> comment;
  }

  public function obtener_fecha_comment(){
    return $this-> fecha_comment;
  }
}
?>
