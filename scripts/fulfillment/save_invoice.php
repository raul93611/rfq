<?php
$invoice = new Invoice('', $_POST['id_rfq'], $_POST['name'], '');
Conexion::abrir_conexion();
InvoiceRepository::insert(Conexion::obtener_conexion(), $invoice);
Conexion::cerrar_conexion();
Redireccion::redirigir(FULFILLMENT . $_POST['id_rfq']);
?>
