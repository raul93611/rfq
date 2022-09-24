<?php
if(isset($_POST['edit_service_button'])){
  Conexion::abrir_conexion();
  ReQuoteServiceRepository::edit_service(Conexion::obtener_conexion(), $_POST['id_service'], htmlspecialchars($_POST['description']), $_POST['quantity'], $_POST['unit_price'], $_POST['quantity']*$_POST['unit_price']);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(RE_QUOTE . $_POST['id_rfq'] . '#service' . $_POST['id_service']);
}
?>
