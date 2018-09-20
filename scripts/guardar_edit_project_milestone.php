<?php
session_start();
if (isset($_POST['editar_project_milestone'])) {
    Conexion::abrir_conexion();
    $cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $_POST['id_cuestionario']);
    $project_milestone_editado = RepositorioProjectMilestone::actualizar_project_milestone(Conexion::obtener_conexion(), $_POST['date_milestone'], $_POST['description'], $_POST['id_project_milestone']);
    Conexion::cerrar_conexion();
    if($project_milestone_editado){
        Redireccion::redirigir(CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq());
    }
}
?>
