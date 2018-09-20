<?php
session_start();
if (isset($_POST['editar_high_level_requirement'])) {
    Conexion::abrir_conexion();
    $cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $_POST['id_cuestionario']);
    $high_level_requirement_editado = RepositorioHighLevelRequirement::actualizar_high_level_requirement(Conexion::obtener_conexion(), $_POST['requirement'], $_POST['id_high_level_requirement']);
    Conexion::cerrar_conexion();
    if($high_level_requirement_editado){
        Redireccion::redirigir(CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq());
    }
}
?>
