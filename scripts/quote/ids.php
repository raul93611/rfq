<?php
header('Content-Type: application/json');

$searchTerm = $_POST['term'];

Conexion::abrir_conexion();
$ids = RepositorioRfq::getIds(Conexion::obtener_conexion(), $searchTerm, $_POST["id_rfq"]);
Conexion::cerrar_conexion();

$response = array();
foreach ($ids as $key => $id) {
  $response[] = array(
    'id' => $id['id'],
    'text' => $id['id']
  );
}
echo json_encode($response);
