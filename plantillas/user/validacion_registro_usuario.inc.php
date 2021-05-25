<?php
if(isset($_POST['registrar_usuario'])){
  Database::open_connection();
  switch ($_POST['cargo']){
    case 'boss':
      $role = 2;
      break;
    case 'head_of_area':
      $role = 3;
      break;
    case 'common_user':
      $role = 4;
      break;
    case 'rookie':
      $role = 5;
      break;
  }
  $validador = new ValidadorRegistro($_POST['nombre_usuario'], $_POST['password1'], $_POST['password2'], $_POST['nombres'], $_POST['apellidos'], Database::get_connection());
  if($validador-> registro_valido()){
    $nuevo_usuario = new Usuario('', $validador-> obtener_nombre_usuario(), password_hash($validador-> obtener_password(), PASSWORD_DEFAULT), $validador-> obtener_nombres(), $validador-> obtener_apellidos(), $role, $_POST['email'], 0, '');
    $usuario_insertado = RepositorioUsuario::insertar_usuario(Database::get_connection(), $nuevo_usuario);
    if($usuario_insertado){
      Redirection::redirect_js(PROFILE);
    }
  }
  Database::close_connection();
}
?>
