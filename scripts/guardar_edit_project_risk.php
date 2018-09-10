<?php
session_start();
if (isset($_POST['editar_project_risk'])) {
    Conexion::abrir_conexion();
    $cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $_POST['id_cuestionario']);
    $project_risk_editado = RepositorioProjectRisk::actualizar_project_risk(Conexion::obtener_conexion(), $_POST['description'], $_POST['id_project_risk']);
    Conexion::cerrar_conexion();
    if($project_risk_editado){
        Redireccion::redirigir(CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq());
    }
}
?>
