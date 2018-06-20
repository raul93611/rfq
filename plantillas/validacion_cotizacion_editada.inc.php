<?php

if (isset($_POST['guardar_cambios_cotizacion'])) {
    Conexion::abrir_conexion();
    $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
    $usuario_antiguo = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
    if ($usuario_antiguo->obtener_nombre_usuario() != $_POST['usuario_designado']) {
        $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
        $usuario_designado = $usuario->obtener_id();
        $cotizacion_editada = RepositorioRfq::actualizar_usuario_designado(Conexion::obtener_conexion(), $usuario_designado, $_POST['id_rfq']);
        $cotizacion_editada1 = RepositorioRfq::actualizar_taxes_profit(Conexion::obtener_conexion(), $_POST['taxes'], $_POST['profit'], $_POST['total_cost'], $_POST['total_price'], $_POST['id_rfq']);
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
    } else {
        $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
        $usuario_designado = $usuario->obtener_id();
        $cotizacion_editada = RepositorioRfq::actualizar_usuario_designado(Conexion::obtener_conexion(), $usuario_designado, $_POST['id_rfq']);
        $cotizacion_editada1 = RepositorioRfq::actualizar_taxes_profit(Conexion::obtener_conexion(), $_POST['taxes'], $_POST['profit'], $_POST['total_cost'], $_POST['total_price'], $_POST['id_rfq']);
    }
    Conexion::cerrar_conexion();
}
if (isset($_POST['guardar_cambios_cotizacion2'])) {
    Conexion::abrir_conexion();
    if(isset($_POST['completado']) && $_POST['completado'] == 'si'){
        $completado = 1;
    }else{
        $completado = 0;
    }
    RepositorioRfq::actualizar_rfq_2(Conexion::obtener_conexion(), $completado, $_POST['comments'], $_POST['ship_via'], htmlspecialchars($_POST['address']), $_POST['payment_terms'], htmlspecialchars($_POST['ship_to']), $_POST['id_rfq']);
    $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
    Conexion::cerrar_conexion();
    
    if($completado){
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
    }
}
?>
