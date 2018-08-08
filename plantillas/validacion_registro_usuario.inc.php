<?php
if(isset($_POST['registrar_usuario'])){
    Conexion::abrir_conexion();
    switch ($_POST['cargo']){
        case 'boss':
            $cargo = 2;
            break;
        case 'head_of_area':
            $cargo = 3;
            break;
        case 'common_user':
            $cargo = 4;
            break;
        case 'rookie':
            $cargo = 5;
            break;
    }

    $validador = new ValidadorRegistro($_POST['nombre_usuario'], $_POST['password1'], $_POST['password2'], $_POST['nombres'], $_POST['apellidos'], Conexion::obtener_conexion());

    if($validador-> registro_valido()){
        $nuevo_usuario = new Usuario('', $validador-> obtener_nombre_usuario(), password_hash($validador-> obtener_password(), PASSWORD_DEFAULT), $validador-> obtener_nombres(), $validador-> obtener_apellidos(), $cargo, $_POST['email']);
        $usuario_insertado = RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $nuevo_usuario);
        if($usuario_insertado){
            Redireccion::redirigir1(PERFIL);
        }
    }
    Conexion::cerrar_conexion();
}
?>
