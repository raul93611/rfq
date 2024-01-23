<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
if ($_POST["partial_invoice"]) {
  InvoiceRepository::updateInvoiceAcceptance(Conexion::obtener_conexion(), $_POST['id'], $_POST["invoice_acceptance"]);
} else {
  RepositorioRfq::updateInvoiceAcceptance(Conexion::obtener_conexion(), $_POST['id'], $_POST["invoice_acceptance"]);
}
Conexion::cerrar_conexion();
echo json_encode(array(
  'reponse' => 'success'
));
