<?php
if(isset($_POST['edit_user'])){
  switch ($_POST['cargo']) {
    case 'boss':
      $cargo_nuevo = 2;
      break;
    case 'head_of_area':
      $cargo_nuevo = 3;
      break;
    case 'common_user':
      $cargo_nuevo = 4;
      break;
    case 'rookie':
      $cargo_nuevo = 5;
      break;
    default:
      break;
  }
  if(empty($_POST['password1'])){
    $password = '';
  }else{
    $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
  }
  Conexion::abrir_conexion();
  $edited_user = RepositorioUsuario::edit_user(Conexion::obtener_conexion(), $password, $_POST['username'], $_POST['nombres'], $_POST['apellidos'], $cargo_nuevo, $_POST['email'], $_POST['id_user']);
  Conexion::cerrar_conexion();
  if($edited_user){
    Redireccion::redirigir1(PERFIL);
  }
}
?>
