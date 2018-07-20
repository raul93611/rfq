<?php
class ValidadorRegistro extends ValidadorUsuario{
    public function __construct($nombre_usuario, $password1, $password2, $nombres, $apellidos, $conexion) {
        $this->aviso_inicio = "<br><div class='alert alert-danger' role='alert'>";
        $this->aviso_cierre = "</div>";

        $this-> nombre_usuario = '';
        $this-> password = '';
        $this-> nombres = '';
        $this-> apellidos = '';

        $this-> error_nombre_usuario = $this-> validar_nombre_usuario($nombre_usuario, $conexion);
        $this-> error_nombres = $this->validar_nombres($nombres);
        $this-> error_apellidos = $this->validar_apellidos($conexion, $apellidos, $nombres);
        $this-> error_password1 = $this->validar_password1($password1);
        $this-> error_password2 = $this->validar_password2($password1, $password2);

        if ($this->error_password1 == '' && $this->error_password2 == '') {
            $this-> password = $password1;
        }
    }
}
?>
