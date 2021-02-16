<?php
class Usuario{
  private $id;
  private $nombre_usuario;
  private $password;
  private $nombres;
  private $apellidos;
  private $cargo;
  private $email;
  private $status;
  private $hash_recover_email;

  public function __construct($id, $nombre_usuario, $password, $nombres, $apellidos, $cargo, $email, $status, $hash_recover_email){
    $this-> id = $id;
    $this-> nombre_usuario = $nombre_usuario;
    $this-> password = $password;
    $this-> nombres = $nombres;
    $this-> apellidos = $apellidos;
    $this-> cargo = $cargo;
    $this-> email = $email;
    $this-> status = $status;
    $this-> hash_recover_email = $hash_recover_email;
  }

  public function obtener_id(){
    return $this-> id;
  }

  public function obtener_nombre_usuario(){
    return $this-> nombre_usuario;
  }

  public function obtener_password(){
    return $this-> password;
  }

  public function obtener_nombres(){
    return $this-> nombres;
  }

  public function obtener_apellidos(){
    return $this-> apellidos;
  }

  public function obtener_cargo(){
    return $this-> cargo;
  }

  public function obtener_email(){
    return $this-> email;
  }

  public function obtener_status(){
    return $this-> status;
  }

  public function obtener_hash_recover_email(){
    return $this-> hash_recover_email;
  }
}
?>