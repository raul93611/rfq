<?php
abstract class ValidadorUsuario {
  protected $aviso_inicio;
  protected $aviso_cierre;

  protected $nombre_usuario;
  protected $password;
  protected $nombres;
  protected $apellidos;
  protected $error_nombre_usuario;
  protected $error_password1;
  protected $error_password2;
  protected $error_nombres;
  protected $error_apellidos;

  public function __construct() {

  }

  protected function variable_iniciada($variable) {
    if (isset($variable) && !empty($variable)) {
      return true;
    } else {
      return false;
    }
  }

  protected function validar_nombre_usuario($nombre_usuario, $conexion) {
    if (!$this->variable_iniciada($nombre_usuario)) {
      return 'Debes introducir nombre de usuario.';
    } else {
      $this->nombre_usuario = $nombre_usuario;
    }
    if (!preg_match("/^[a-zA-Z0123456789]*$/", $nombre_usuario)) {
      return 'Caracteres no permitidos.';
    }
    if(RepositorioUsuario::nombre_usuario_existe($conexion, $nombre_usuario)){
      return 'Nombre de usuario en uso.';
    }
    return '';
  }

  protected function validar_nombres($nombres) {
    if (!$this->variable_iniciada($nombres)) {
      return 'Debes introducir tu(s) nombre(s).';
    } else {
      $this->nombres = $nombres;
    }
    if (!preg_match("/^[a-zA-Z áéíóúÁÉÍÓÚÑñ]*$/", $nombres)) {
      return 'Caracteres no permitidos.';
    }
    return '';
  }

  protected function validar_apellidos($conexion, $apellidos, $nombres) {
    if (!$this->variable_iniciada($nombres)) {
      return 'Introduce tu(s) nombre(s) y apellido(s).';
    }
    if (!$this->variable_iniciada($apellidos)) {
      return 'Introduce tus apellido(s).';
    } else {
      $this->apellidos = $apellidos;
    }
    if (RepositorioUsuario::nombre_completo_existe($conexion, $apellidos, $nombres)) {
      return 'Tus nombres y apellidos ya existen.';
    }
    return '';
  }

  protected function validar_password1($password1) {
    if (!$this->variable_iniciada($password1)) {
      return 'Debes introducir una contraseña.';
    }
    return '';
  }

  protected function validar_password2($password1, $password2) {
    if (!$this->variable_iniciada($password1)) {
      return 'Debes introducir una contraseña.';
    }
    if (!$this->variable_iniciada($password2)) {
      return 'Debes repetir tu contraseña.';
    }
    if ($password1 != $password2) {
      return 'Las contraseñas deben ser la mismas.';
    }
    return '';
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

  public function obtener_error_nombre_usuario(){
    return $this-> error_nombre_usuario;
  }

  public function obtener_error_password1(){
    return $this-> error_password1;
  }

  public function obtener_error_passsword2(){
    return $this-> error_password2;
  }

  public function obtener_error_nombres(){
    return $this-> error_nombres;
  }

  public function obtener_error_apellidos(){
    return $this-> error_apellidos;
  }

  public function mostrar_nombre_usuario(){
    if($this-> nombre_usuario != ''){
      echo 'value="' . $this-> nombre_usuario . '"';
    }
  }

  public function mostrar_nombres(){
    if($this-> nombres != ''){
      echo 'value="' . $this-> nombres . '"';
    }
  }

  public function mostrar_apellidos(){
    if($this-> apellidos != ''){
      echo 'value="' . $this-> apellidos . '"';
    }
  }

  public function mostrar_error_nombre_usuario(){
    if($this-> error_nombre_usuario != ''){
      echo $this-> aviso_inicio . $this-> error_nombre_usuario . $this-> aviso_cierre;
    }
  }

  public function mostrar_error_password1(){
    if($this-> error_password1 != ''){
      echo $this-> aviso_inicio . $this-> error_password1 . $this-> aviso_cierre;
    }
  }

  public function mostrar_error_password2(){
    if($this-> error_password2 != ''){
      echo $this-> aviso_inicio . $this-> error_password2 . $this-> aviso_cierre;
    }
  }

  public function mostrar_error_nombres(){
    if($this-> error_nombres != ''){
      echo $this-> aviso_inicio . $this-> error_nombres . $this-> aviso_cierre;
    }
  }

  public function mostrar_error_apellidos(){
    if($this-> error_apellidos != ''){
      echo $this-> aviso_inicio . $this-> error_apellidos . $this-> aviso_cierre;
    }
  }

  public function registro_valido(){
    if($this-> error_nombre_usuario == '' && $this-> error_password1 == '' && $this-> error_password2 == '' && $this-> error_nombres == '' && $this-> error_apellidos == ''){
      return true;
    }else{
      return false;
    }
  }
}
?>
