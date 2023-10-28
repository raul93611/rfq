<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
RepositorioRfq::updatePaymentTerms(Conexion::obtener_conexion(), $_POST["paymentTerms"], $_POST["id"]);
RepositorioItem::updateItemsPrices(Conexion::obtener_conexion(), $_POST["id"]);
RepositorioSubitem::updateSubitemsPrices(Conexion::obtener_conexion(), $_POST["id"]);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id' => $_POST['id']
));
