<?php

if (isset($_POST['guardar_cambios_cotizacion'])) {
    $id_rfq = $_POST['id_rfq'];
    Conexion::abrir_conexion();
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    $canal = $cotizacion->obtener_canal();
    switch ($canal) {
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
        case 'FBO':
            $canal = 'fbo';
            break;
    }

    Conexion::abrir_conexion();
    if (isset($_POST['status']) && $_POST['status'] == 'si') {
        $status = 1;
    } else {
        $status = 0;
    }

    if ($_POST['completado_antiguo']) {
        $completado = 1;
        $ruta_completado = 1;
    } else {
        if (isset($_POST['completado']) && $_POST['completado'] == 'si') {
            $completado = 1;
        } else {
            $completado = 0;
        }
        $ruta_completado = 0;
    }

    if (isset($_POST['award']) && $_POST['award'] == 'si') {
        $award = 1;
    } else {
        $award = 0;
    }

    if (isset($_POST['completado']) && $_POST['completado'] == 'si') {
        $fecha_completado = date('Y-m-d');
        $expiration_date = strtotime('+1 month', strtotime($fecha_completado));
        $expiration_date = date('Y-m-d', $expiration_date);
    } else {
        $fecha_completado = $_POST['fecha_completado'];
        $expiration_date = $_POST['expiration_date'];
    }

    $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
    $usuario_designado = $usuario->obtener_id();
    #$validador = new ValidadorCotizacionEdicion($_POST['amount']);
    #if ($validador->obtener_error_amount() == '') {
    $cotizacion_editada = RepositorioRfq::actualizar_cotizacion(Conexion::obtener_conexion(), $_POST['id_rfq'], $usuario_designado, $status, $completado, $_POST['comments'], $award, $fecha_completado, $_POST['payment_terms'], htmlspecialchars($_POST['address']), htmlspecialchars($_POST['ship_to']), $expiration_date, $_POST['ship_via']);
    if ($cotizacion_editada) {
        $directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $_POST['id_rfq'];
        $documentos = array_filter($_FILES['documentos']['name']);
        $total = count($documentos);
        for ($i = 0; $i < $total; $i++) {
            $tmp_path = $_FILES['documentos']['tmp_name'][$i];

            if ($tmp_path != '') {
                $new_path = $directorio . '/' . $_FILES['documentos']['name'][$i];
                move_uploaded_file($tmp_path, $new_path);
            }
        }
        if($ruta_completado){
            Redireccion::redirigir1(COMPLETADOS . $canal);
        }else{
            Redireccion::redirigir1(COTIZACIONES . $canal);
        }
        
    }
    #}
    Conexion::cerrar_conexion();
}
?>
