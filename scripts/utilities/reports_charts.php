<?php
header('Content-Type: application/json');
switch ($_POST['report']) {
  case 'award':
    Conexion::abrir_conexion();
    $data = Report::award_chart(Conexion::obtener_conexion(), 'yearly', $_POST['quarter'], $_POST['month'], $_POST['year']);
    Conexion::cerrar_conexion();
    break;
  default:
    break;
}
echo json_encode($data);
