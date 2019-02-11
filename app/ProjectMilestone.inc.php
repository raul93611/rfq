<?php
class ProjectMilestone{
  private $id;
  private $id_cuestionario;
  private $date_milestone;
  private $description;

  public function __construct($id, $id_cuestionario, $date_milestone, $description){
    $this-> id = $id;
    $this-> id_cuestionario = $id_cuestionario;
    $this-> date_milestone = $date_milestone;
    $this-> description = $description;
  }

  public function obtener_id(){
    return $this-> id;
  }

  public function obtener_id_cuestionario(){
    return $this-> id_cuestionario;
  }

  public function obtener_date_milestone(){
    return $this-> date_milestone;
  }

  public function obtener_description(){
    return $this-> description; 
  }
}
?>
