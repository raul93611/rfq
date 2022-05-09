<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$providers = ProviderListRepository::get_all(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
$json = [];
foreach ($providers as $key => $provider) {
  $row = [
    $provider-> get_company_name(),
    '<button type="button" data="' . $provider-> get_id() . '" class="edit_button btn btn-info btn-sm" name=""><i class="fas fa-pen"></i></button>
    <button type="button" data="' . $provider-> get_id() . '" class="delete_button btn btn-danger btn-sm" name=""><i class="fas fa-trash"></i></button>'
  ];
  array_push($json, $row);
}
echo json_encode(array(
  'data' => $json
));
?>
