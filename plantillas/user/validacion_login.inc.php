<?php
if(isset($_POST['create_session'])){
  Database::open_connection();
  $validador = new ValidadorLogin($_POST['nombre_usuario'], $_POST['password'], Database::get_connection());
  if($validador-> obtener_error() == '' && !is_null($validador-> obtener_usuario())){
    SessionControl::create_session($validador-> obtener_usuario()-> obtener_id(), $validador-> obtener_usuario()-> obtener_nombre_usuario(), $validador-> obtener_usuario()-> obtener_cargo());
    Redirection::redirect_js(PROFILE);
  }
  Database::close_connection();
}
?>
