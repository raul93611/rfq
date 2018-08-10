<?php
if(isset($_POST['guardar_provider_subitem'])){
    Conexion::abrir_conexion();
    $provider_subitem = new ProviderSubitem('', $_POST['id_subitem'], $_POST['provider'], $_POST['price']);
    RepositorioProviderSubitem::insertar_provider_subitem(Conexion::obtener_conexion(), $provider_subitem);
    $subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem-> obtener_id_subitem());
    $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
    $id_rfq = $item-> obtener_id_rfq();
    Conexion::cerrar_conexion();
    Redireccion::redirigir1(EDITAR_COTIZACION . '/' . $id_rfq);
}
?>
