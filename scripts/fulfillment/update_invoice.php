<?php
Conexion::abrir_conexion();
InvoiceRepository::update(Conexion::obtener_conexion(), $_POST['name'], $_POST['id_invoice']);
Conexion::cerrar_conexion();
Redireccion::redirigir(FULFILLMENT . $_POST['id_rfq']);
?>
