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

  public function obtener_cargo_string(){
    return $this-> cargo;
  }

  public function obtener_cargo(){
    return explode(',', $this-> cargo);
  }

  public function is_admin(){
    if(in_array(1, $this-> obtener_cargo())){
      return true;
    }
    return false;
  }

  public function is_fulfillment(){
    if(in_array(2, $this-> obtener_cargo())){
      return true;
    }
    return false;
  }

  public function is_rfq(){
    if(in_array(3, $this-> obtener_cargo())){
      return true;
    }
    return false;
  }

  public function is_accounting(){
    if(in_array(4, $this-> obtener_cargo())){
      return true;
    }
    return false;
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

  public function obtener_role(){
    $display_role = [];
    foreach ($this-> obtener_cargo() as $key => $role) {
      switch ($role) {
        case 2:
        $display_role[] = 'Fulfillment';
        break;
        case 3:
        $display_role[] = 'RFQ';
        break;
        case 4:
        $display_role[] = 'Accounting';
        break;
        default:
        break;
      }
    }
    return implode(',', $display_role);
  }
}
?>
