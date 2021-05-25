<?php
if(isset($_POST['edit_user'])){
  switch ($_POST['cargo']) {
    case 'boss':
      $role_nuevo = 2;
      break;
    case 'head_of_area':
      $role_nuevo = 3;
      break;
    case 'common_user':
      $role_nuevo = 4;
      break;
    case 'rookie':
      $role_nuevo = 5;
      break;
    default:
      break;
  }
  if(empty($_POST['password1'])){
    $password = '';
  }else{
    $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
  }
  Database::open_connection();
  $edited_user = RepositorioUsuario::edit_user(Database::get_connection(), $password, $_POST['username'], $_POST['nombres'], $_POST['apellidos'], $role_nuevo, $_POST['email'], $_POST['id_user']);
  Database::close_connection();
  if($edited_user){
    Redirection::redirect_js(PROFILE);
  }
}
?>
