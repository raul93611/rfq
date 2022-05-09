<?php
class TaskComment{
  private $id;
  private $id_task;
  private $id_user;
  private $comment;
  private $created_at;

  public function __construct($id, $id_task, $id_user, $comment, $created_at){
    $this-> id = $id;
    $this-> id_task = $id_task;
    $this-> id_user = $id_user;
    $this-> comment = $comment;
    $this-> created_at = $created_at;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_task(){
    return $this-> id_task;
  }

  public function get_id_user(){
    return $this-> id_user;
  }

  public function get_comment(){
    return $this-> comment;
  }

  public function get_created_at(){
    return $this-> created_at;
  }

  public function get_id_user_name(){
    Conexion::abrir_conexion();
    $user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $this-> id_user);
    Conexion::cerrar_conexion();
    return $user-> obtener_nombre_usuario();
  }
}
?>
