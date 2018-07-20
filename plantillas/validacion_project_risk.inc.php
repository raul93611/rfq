<?php
if (isset($_POST['guardar_project_risk'])) {
    Conexion::abrir_conexion();
    $cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $_POST['id_cuestionario']);
    $project_risk = new ProjectRisk('', $_POST['id_cuestionario'], $_POST['description']);
    $project_risk_insertado = RepositorioProjectRisk::insertar_project_risk(Conexion::obtener_conexion(), $project_risk);
    Conexion::cerrar_conexion();
    if($project_risk_insertado){
        Redireccion::redirigir1(CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq());
    }
}
?>

