<?php
Conexion::abrir_conexion();
$services = ServiceRepository::get_services(Conexion::obtener_conexion(), 86941);
if(count($services)){
  foreach ($services as $key => $service) {
    echo 'done';
    $re_quote_service = new ReQuoteService('', 1173, $service-> get_description(), $service-> get_quantity(), $service-> get_unit_price(), $service-> get_total_price());
    ReQuoteServiceRepository::insert(Conexion::obtener_conexion(), $re_quote_service);
  }
}
Conexion::cerrar_conexion();
?>