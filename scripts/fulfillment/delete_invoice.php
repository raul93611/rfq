<?php
Conexion::abrir_conexion();
$invoice = InvoiceRepository::get_one(Conexion::obtener_conexion(), $id_invoice);
InvoiceRepository::delete(Conexion::obtener_conexion(), $id_invoice);
Conexion::cerrar_conexion();
Redireccion::redirigir(FULFILLMENT . $invoice->get_id_rfq());
?>
