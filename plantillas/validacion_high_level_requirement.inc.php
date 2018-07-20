<?php
if (isset($_POST['guardar_high_level_requirement'])) {
    Conexion::abrir_conexion();
    $cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $_POST['id_cuestionario']);
    $high_level_requirement = new HighLevelRequirement('', $_POST['id_cuestionario'], $_POST['requirement']);
    $high_level_requirement_insertado = RepositorioHighLevelRequirement::insertar_high_level_requirement(Conexion::obtener_conexion(), $high_level_requirement);
    Conexion::cerrar_conexion();
    if($high_level_requirement_insertado){
        Redireccion::redirigir1(CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq());
    }
}
?>

