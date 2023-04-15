<?php
class ControlSesion{
  public static function iniciar_sesion($user){
    if(session_id() == ''){
      session_start();
    }

    $_SESSION['user'] = $user;
  }

  public static function cerrar_sesion(){
    if(session_id() == ''){
      session_start();
    }

    if(isset($_SESSION['user'])){
      unset($_SESSION['user']);
    }

    session_destroy();
  }

  public static function sesion_iniciada(){
    if(session_id() == ''){
      session_start();
    }

    if(isset($_SESSION['user'])){
      return true;
    }else{
      return false;
    }
  }
}
?>
