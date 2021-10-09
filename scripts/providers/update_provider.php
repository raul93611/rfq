<?php
header('Content-Type: application/json');
$provider = ProviderListRepository::validate_provider($_POST);
if(!$provider){
  $result = false;
}else{
  $result = true;
  Conexion::abrir_conexion();
  ProviderListRepository::update(Conexion::obtener_conexion(), $provider, $_POST['id_provider']);
  Conexion::cerrar_conexion();
}
echo json_encode(array(
  'result' => $result,
));
?>
