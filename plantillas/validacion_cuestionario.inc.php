<?php
if(isset($_POST['registrar_cuestionario'])){
    Conexion::abrir_conexion();
    RepositorioCuestionario::actualizar_cuestionario(Conexion::obtener_conexion(), $_POST['id_cuestionario'], $_POST['reach_objectives'], $_POST['cost_objectives'], $_POST['time_objectives'], $_POST['quality_objectives'], $_POST['reach_goals'], $_POST['cost_goals'], $_POST['time_goals'], $_POST['quality_goals']);
    $cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $_POST['id_cuestionario']);
    Conexion::cerrar_conexion();
    
}
?>
