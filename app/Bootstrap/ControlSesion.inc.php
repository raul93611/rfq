<?php
class ControlSesion{
  public static function iniciar_sesion($user){
    if(session_id() == ''){
      session_start();
    }

    $_SESSION['user'] = $user;

    // $_SESSION['id_usuario'] = $id_usuario;
    // $_SESSION['nombre_usuario'] = $nombre_usuario;
    // $_SESSION['cargo'] = $cargo;
  }

  public static function cerrar_sesion(){
    if(session_id() == ''){
      session_start();
    }

    if(isset($_SESSION['user'])){
      unset($_SESSION['user']);
    }

    // if(isset($_SESSION['nombre_usuario'])){
    //   unset($_SESSION['nombre_usuario']);
    // }
    //
    // if(isset($_SESSION['cargo'])){
    //   unset($_SESSION['cargo']);
    // }

    session_destroy();
  }

  public static function sesion_iniciada(){
    if(session_id() == ''){
      session_start();
    }

    // if(isset($_SESSION['id_usuario']) && isset($_SESSION['nombre_usuario']) && isset($_SESSION['cargo'])){
    if(isset($_SESSION['user'])){
      return true;
    }else{
      return false;
    }
  }
}
?>
