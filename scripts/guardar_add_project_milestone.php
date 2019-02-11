<?php
session_start();
if (isset($_POST['guardar_project_milestone'])) {
  Conexion::abrir_conexion();
  $cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $_POST['id_cuestionario']);
  $project_milestone = new ProjectMilestone('', $_POST['id_cuestionario'], $_POST['date_milestone'], $_POST['description']);
  $project_milestone_insertado = RepositorioProjectMilestone::insertar_project_milestone(Conexion::obtener_conexion(), $project_milestone);
  Conexion::cerrar_conexion();
  if($project_milestone_insertado){
    Redireccion::redirigir(CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq());
  }
}
?>
