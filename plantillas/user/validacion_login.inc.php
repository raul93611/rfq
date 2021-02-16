<?php
if(isset($_POST['iniciar_sesion'])){
  Conexion::abrir_conexion();
  $validador = new ValidadorLogin($_POST['nombre_usuario'], $_POST['password'], Conexion::obtener_conexion());
  if($validador-> obtener_error() == '' && !is_null($validador-> obtener_usuario())){
    ControlSesion::iniciar_sesion($validador-> obtener_usuario()-> obtener_id(), $validador-> obtener_usuario()-> obtener_nombre_usuario(), $validador-> obtener_usuario()-> obtener_cargo());
    Redireccion::redirigir1(PERFIL);
  }
  Conexion::cerrar_conexion();
}
?>
