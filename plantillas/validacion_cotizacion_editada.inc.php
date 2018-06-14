<?php
if (isset($_POST['guardar_cambios_cotizacion'])) {
    Conexion::abrir_conexion();
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
    $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
    $usuario_designado = $usuario->obtener_id();
    $cotizacion_editada = RepositorioRfq::actualizar_usuario_designado(Conexion::obtener_conexion(), $usuario_designado, $_POST['id_rfq']);
    Conexion::cerrar_conexion();
    if ($cotizacion_editada) {
        switch ($cotizacion->obtener_canal()) {
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
    }
}
?>
