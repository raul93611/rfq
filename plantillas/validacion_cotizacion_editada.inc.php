<?php
if (isset($_POST['guardar_cambios_cotizacion'])) {
    Conexion::abrir_conexion();
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
    $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
    $usuario_designado = $usuario->obtener_id();
    $cotizacion_editada = RepositorioRfq::actualizar_usuario_designado(Conexion::obtener_conexion(), $usuario_designado, $_POST['id_rfq']);
    $cotizacion_editada1 = RepositorioRfq::actualizar_taxes_profit(Conexion::obtener_conexion(), $_POST['taxes'], $_POST['profit'], $_POST['total_cost'], $_POST['total_price'], $_POST['id_rfq']);
    Conexion::cerrar_conexion();
}
?>
