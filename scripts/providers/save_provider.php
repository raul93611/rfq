<?php
header('Content-Type: application/json');
$provider = ProviderListRepository::validate_provider($_POST);
if(!$provider){
  $result = false;
}else{
  $result = true;
  Conexion::abrir_conexion();
  ProviderListRepository::insert(Conexion::obtener_conexion(), $provider);
  Conexion::cerrar_conexion();
}
echo json_encode(array(
  'result' => $result,
));
?>
