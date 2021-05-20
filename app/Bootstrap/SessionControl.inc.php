<?php
class SessionControl{
  public static function create_session($id_user, $username, $role){
    if(session_id() == ''){
      session_start();
    }

    $_SESSION['id_user'] = $id_user;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
  }

  public static function destroy_session(){
    if(session_id() == ''){
      session_start();
    }

    if(isset($_SESSION['id_user'])){
      unset($_SESSION['id_user']);
    }

    if(isset($_SESSION['username'])){
      unset($_SESSION['username']);
    }

    if(isset($_SESSION['role'])){
      unset($_SESSION['role']);
    }

    session_destroy();
  }

  public static function has_session(){
    if(session_id() == ''){
      session_start();
    }

    if(isset($_SESSION['id_user']) && isset($_SESSION['username']) && isset($_SESSION['role'])){
      return true;
    }else{
      return false;
    }
  }
}
?>
