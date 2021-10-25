<?php
class Task{
  private $id;
  private $id_user;
  private $assigned_user;
  private $id_rfq;
  private $title;
  private $message;
  private $status;

  public function __construct($id, $id_user, $assigned_user, $id_rfq, $title, $message, $status){
    $this-> id = $id;
    $this-> id_user = $id_user;
    $this-> assigned_user = $assigned_user;
    $this-> id_rfq = $id_rfq;
    $this-> title = $title;
    $this-> message = $message;
    $this-> status = $status;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_user(){
    return $this-> id_user;
  }

  public function get_assigned_user(){
    return $this-> assigned_user;
  }

  public function get_id_rfq(){
    return $this-> id_rfq;
  }

  public function get_title(){
    return $this-> title;
  }

  public function get_message(){
    return $this-> message;
  }

  public function get_status(){
    return $this-> status;
  }

  public function get_id_user_name(){
    Conexion::abrir_conexion();
    $user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $this-> id_user);
    Conexion::cerrar_conexion();
    return $user-> obtener_nombre_usuario();
  }

  public function get_assigned_user_name(){
    Conexion::abrir_conexion();
    $user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $this-> assigned_user);
    Conexion::cerrar_conexion();
    return $user-> obtener_nombre_usuario();
  }

  public function get_users_to_notify($auth_user){
    $users = [];
    Conexion::abrir_conexion();
    if($auth_user == $this-> id_user){
      $users[] = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $this-> assigned_user);
    }else if($auth_user == $this-> assigned_user){
      $users[] = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $this-> id_user);
    }else{
      $users[] = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $this-> assigned_user);
      $users[] = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $this-> id_user);
    }
    Conexion::cerrar_conexion();
    return $users;
  }
}
?>
