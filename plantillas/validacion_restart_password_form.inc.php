<?php
if(isset($_POST['send'])){
  $error = false;
  if(isset($_POST['password1']) && !empty($_POST['password1']) && isset($_POST['password2']) && !empty($_POST['password2'])){
    if($_POST['password1'] == $_POST['password2']){
      $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
      Conexion::abrir_conexion();
      $url_secreta_existe = RepositorioUsuario::url_secreta_existe(Conexion::obtener_conexion(), $_POST['url_secreta']);
      if($url_secreta_existe){
        $usuario = RepositorioUsuario::obtener_usuario_por_url_secreta(Conexion::obtener_conexion(), $_POST['url_secreta']);
        RepositorioUsuario::actualizar_clave(Conexion::obtener_conexion(), $password, $usuario-> obtener_id());
        RepositorioUsuario::eliminar_hash_recover_email(Conexion::obtener_conexion(), $usuario-> obtener_id());
      }else {
        $error = true;
      }
      Conexion::cerrar_conexion();
    }else{
      $error = true;
    }
  }else{
    $error = true;
  }
}
?>
