<?php
if(isset($_POST['send'])){
  $error = false;
  if(isset($_POST['password1']) && !empty($_POST['password1']) && isset($_POST['password2']) && !empty($_POST['password2'])){
    if($_POST['password1'] == $_POST['password2']){
      $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
      Database::open_connection();
      $url_secreta_existe = RepositorioUsuario::url_secreta_existe(Database::get_connection(), $_POST['url_secreta']);
      if($url_secreta_existe){
        $usuario = RepositorioUsuario::obtener_usuario_por_url_secreta(Database::get_connection(), $_POST['url_secreta']);
        RepositorioUsuario::actualizar_clave(Database::get_connection(), $password, $usuario-> obtener_id());
        RepositorioUsuario::eliminar_hash_recover_email(Database::get_connection(), $usuario-> obtener_id());
      }else {
        $error = true;
      }
      Database::close_connection();
    }else{
      $error = true;
    }
  }else{
    $error = true;
  }
}
?>
