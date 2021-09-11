<?php
if(isset($_POST['edit_user'])){
  if(empty($_POST['password1'])){
    $password = '';
  }else{
    $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
  }
  Conexion::abrir_conexion();
  $edited_user = RepositorioUsuario::edit_user(Conexion::obtener_conexion(), $password, $_POST['username'], $_POST['nombres'], $_POST['apellidos'], $_POST['cargo'], $_POST['email'], $_POST['id_user']);
  Conexion::cerrar_conexion();
  if($edited_user){
    Redireccion::redirigir1(PERFIL);
  }
}
?>
