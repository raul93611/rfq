<?php
if(isset($_POST['add_service_button'])){
  $service = new Service('', $_POST['id_rfq'], htmlspecialchars($_POST['description']), $_POST['quantity'], $_POST['unit_price'], $_POST['quantity']*$_POST['unit_price'], null);
  Conexion::abrir_conexion();
  $id = ServiceRepository::store_service(Conexion::obtener_conexion(), $service);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#service' . $id);
}
?>
