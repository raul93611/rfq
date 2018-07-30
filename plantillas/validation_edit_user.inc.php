<?php
if(isset($_POST['edit_user'])){
    Conexion::abrir_conexion();
    $edited_user = RepositorioUsuario::edit_user(Conexion::obtener_conexion(), password_hash($_POST['password1'], PASSWORD_DEFAULT), $_POST['id_user']);
    if($edited_user){
        Redireccion::redirigir1(PERFIL);
    }
    Conexion::cerrar_conexion();
}
?>
