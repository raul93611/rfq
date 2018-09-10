<?php
session_start();
if (isset($_POST['guardar_cambios_item'])) {
    Conexion::abrir_conexion();
    $item_editado = RepositorioItem::actualizar_item(Conexion::obtener_conexion(), $_POST['id_item'], $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['description_project']), $_POST['quantity'], $_POST['comments'], $_POST['website']);
    Conexion::cerrar_conexion();
    if($item_editado){
        Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#caja_items');
    }
}
?>
