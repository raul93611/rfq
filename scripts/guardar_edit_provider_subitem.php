<?php
session_start();
if (isset($_POST['guardar_cambios_provider_subitem'])) {
    Conexion::abrir_conexion();
    RepositorioProviderSubitem::actualizar_provider_subitem(Conexion::obtener_conexion(), $_POST['id_provider_subitem'], $_POST['provider'], $_POST['price']);
    Conexion::cerrar_conexion();
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#caja_items');
}
?>
