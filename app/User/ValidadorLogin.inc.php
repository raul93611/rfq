<?php
class ValidadorLogin {
  private $usuario;
  private $error;
  private $aviso_inicio;
  private $aviso_cierre;

  public function __construct($nombre_usuario, $password, $conexion) {
    $this->aviso_inicio = "<br><label class='text-danger'>";
    $this->aviso_cierre = "</label>";
    $this->error = '';
    if (!$this->variable_iniciada($nombre_usuario) || !$this->variable_iniciada($password)) {
      $this->usuario = null;
      $this->error = 'Must be filled out.';
    } else {
      $this->usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario($conexion, $nombre_usuario);
      if (is_null($this->usuario) || !password_verify($password, $this->usuario->obtener_password()) || !$this-> usuario-> obtener_status()) {
        $this->error = 'Wrong values.';
      }
    }
  }

  public function variable_iniciada($variable) {
    if (isset($variable) && !empty($variable)) {
      return true;
    } else {
      return false;
    }
  }

  public function obtener_usuario() {
    return $this->usuario;
  }

  public function obtener_error() {
    return $this->error;
  }

  public function mostrar_error() {
    if ($this->error != '') {
      echo $this->aviso_inicio . $this->error . $this->aviso_cierre;
    }
  }

}
