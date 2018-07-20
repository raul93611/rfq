<?php
if (isset($_POST['editar_out_of_scope'])) {
    Conexion::abrir_conexion();
    $cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $_POST['id_cuestionario']);
    $out_of_scope_editado = RepositorioOutOfScope::actualizar_out_of_scope(Conexion::obtener_conexion(), $_POST['requirement'], $_POST['id_out_of_scope']);
    Conexion::cerrar_conexion();
    if($out_of_scope_editado){
        Redireccion::redirigir1(CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq());
    }
}
?>



