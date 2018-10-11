<?php
class Projectrisk{
  private $id;
  private $id_cuestionario;
  private $description;

  public function __construct($id, $id_cuestionario, $description){
    $this-> id = $id;
    $this-> id_cuestionario = $id_cuestionario;
    $this-> description = $description;
  }

  public function obtener_id(){
    return $this-> id;
  }

  public function obtener_id_cuestionario(){
    return $this-> id_cuestionario;
  }

  public function obtener_description(){
    return $this-> description; 
  }
}
?>
