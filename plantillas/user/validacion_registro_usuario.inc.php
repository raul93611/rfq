<?php
if(isset($_POST['registrar_usuario'])){
  Conexion::abrir_conexion();
  $validador = new ValidadorRegistro($_POST['nombre_usuario'], $_POST['password1'], $_POST['password2'], $_POST['nombres'], $_POST['apellidos'], Conexion::obtener_conexion());
  if($validador-> registro_valido()){
    $nuevo_usuario = new Usuario('', $validador-> obtener_nombre_usuario(), password_hash($validador-> obtener_password(), PASSWORD_DEFAULT), $validador-> obtener_nombres(), $validador-> obtener_apellidos(), implode(',', $_POST['cargo']), $_POST['email'], 0, '');
    echo $nuevo_usuario-> obtener_cargo();
    $usuario_insertado = RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $nuevo_usuario);
    if($usuario_insertado){
      Redireccion::redirigir1(USERS);
    }
  }
  Conexion::cerrar_conexion();
}
?>
