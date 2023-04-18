<?php
if(isset($_POST['iniciar_sesion'])){
  Conexion::abrir_conexion();
  $validador = new ValidadorLogin($_POST['nombre_usuario'], $_POST['password'], Conexion::obtener_conexion());
  Conexion::cerrar_conexion();
  if($validador-> obtener_error() == '' && !is_null($validador-> obtener_usuario())){
    ControlSesion::iniciar_sesion($validador-> obtener_usuario());
    // if($_SESSION['user']-> is_admin() || $_SESSION['user']-> is_fulfillment() || $_SESSION['user']-> is_accounting()){
    //   Redireccion::redirigir1(ALL_TASKS);
    // }else if($_SESSION['user']-> is_rfq()){
    //   Redireccion::redirigir1(CHARTS);
    // }
    Redireccion::redirigir1(ALL_TASKS);
  }
}
?>
