<?php
Conexion::abrir_conexion();
echo $cotizacion_recuperada-> obtener_id();
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_id());
echo count($items);
foreach ($items as $item) {
  echo $item-> obtener_id();
    RepositorioItem::delete_item(Conexion::obtener_conexion(), $item->obtener_id());
}
RepositorioCuestionario::delete_cuestionario_por_id_rfq(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_id());
RepositorioRfq::delete_quote(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_id());
Conexion::cerrar_conexion();
switch ($cotizacion_recuperada->obtener_canal()) {
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
Redireccion::redirigir1(COTIZACIONES . $canal);
?>
