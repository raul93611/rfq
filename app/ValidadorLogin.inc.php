<?php
class ValidadorLogin extends ValidadorUsuario {
    private $usuario;
    private $error;

    public function __construct($nombre_usuario, $password, $conexion) {
        $this->aviso_inicio = "<br><div class='alert alert-danger' role='alert'>";
        $this->aviso_cierre = "</div>";
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
?>
