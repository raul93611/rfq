<?php
if(isset($_POST['iniciar_sesion'])){
  Database::open_connection();
  $validador = new ValidadorLogin($_POST['nombre_usuario'], $_POST['password'], Database::get_connection());
  if($validador-> obtener_error() == '' && !is_null($validador-> obtener_usuario())){
    ControlSesion::iniciar_sesion($validador-> obtener_usuario()-> obtener_id(), $validador-> obtener_usuario()-> obtener_nombre_usuario(), $validador-> obtener_usuario()-> obtener_cargo());
    Redireccion::redirigir1(PROFILE);
  }
  Database::close_connection();
}
?>
