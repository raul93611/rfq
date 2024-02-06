<?php
Conexion::abrir_conexion();
$service = ServiceRepository::get_service(Conexion::obtener_conexion(), $id_service);
$duplicated_service = new Service('', $service->get_id_rfq(), $service->get_description(), $service->get_quantity(), $service->get_unit_price(), $service->get_total_price(), null);
$id = ServiceRepository::store_service(Conexion::obtener_conexion(), $duplicated_service);
Conexion::cerrar_conexion();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $service->get_id_rfq() . '#service' . $id);
