<?php
if(!ControlSesion::sesion_iniciada() || $cargo != 1){
    Redireccion::redirigir1(PERFIL);
}

if(isset($_POST['eliminar_usuario'])){
    Conexion::abrir_conexion();
    $id_usuario = $_POST['id_usuario'];
    $usuario_eliminado = RepositorioUsuario::eliminar_usuario(Conexion::obtener_conexion(), $id_usuario);
    Conexion::cerrar_conexion();
    
    if($usuario_eliminado){
        Redireccion::redirigir1(PERFIL);
    }
}
?>

