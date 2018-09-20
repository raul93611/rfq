<?php
session_start();
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $cotizacion-> obtener_id());
foreach ($items as $item) {
    RepositorioItem::delete_item(Conexion::obtener_conexion(), $item->obtener_id());
}
RepositorioCuestionario::delete_cuestionario_por_id_rfq(Conexion::obtener_conexion(), $cotizacion-> obtener_id());
RepositorioRfq::delete_quote(Conexion::obtener_conexion(), $cotizacion-> obtener_id());
Conexion::cerrar_conexion();
switch ($cotizacion-> obtener_canal()) {
    case 'GSA-Buy':
        $canal = 'gsa_buy';
        break;
    case 'FedBid':
        $canal = 'fedbid';
        break;
    case 'E-mails':
        $canal = 'emails';
        break;
    case 'FindFRP':
        $canal = 'findfrp';
        break;
    case 'Embassies':
        $canal = 'embassies';
        break;
    case 'FBO':
        $canal = 'fbo';
        break;
}
Redireccion::redirigir(COTIZACIONES . $canal);
?>
