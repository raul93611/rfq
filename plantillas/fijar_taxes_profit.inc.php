<?php
if(isset($_POST['fijar_taxes_profit'])){
    Conexion::abrir_conexion();
    $cotizacion_editada = RepositorioRfq::actualizar_taxes_profit(Conexion::obtener_conexion(), $_POST['taxes'], $_POST['profit'], $_POST['id_rfq']);
    Conexion::cerrar_conexion();
}
?>
