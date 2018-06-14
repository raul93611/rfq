<?php
if (isset($_POST['guardar_item'])) {
    Conexion::abrir_conexion();
    $item = new Item('', $_POST['id_rfq'], $_SESSION['id_usuario'], $_POST['brand'], $_POST['part_number'], htmlspecialchars($_POST['description']), $_POST['quantity']);
    $item_insertado = RepositorioItem::insertar_item(Conexion::obtener_conexion(), $item);
    Conexion::cerrar_conexion();
    if($item_insertado){
        Redireccion::redirigir1(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
    }
}
?>
