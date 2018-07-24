<?php

if (isset($_POST['guardar_cambios_cotizacion'])) {
    Conexion::abrir_conexion();
    $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
    $usuario_antiguo = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_usuario_designado());
    $id_items = explode(',', $_POST['id_items']);
    $partes_total_price = explode(',', $_POST['partes_total_price']);
    $unit_prices = explode(',', $_POST['unit_prices']);
    $additional = explode(',', $_POST['additional']);
    for ($i = 0; $i < count($id_items); $i++) {
        RepositorioItem::insertar_calculos(Conexion::obtener_conexion(), $unit_prices[$i], $partes_total_price[$i], $additional[$i], $id_items[$i]);
    }

    $directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
    $documentos = array_filter($_FILES['documentos']['name']);
    $total = count($documentos);
    for ($i = 0; $i < $total; $i++) {
        $tmp_path = $_FILES['documentos']['tmp_name'][$i];

        if ($tmp_path != '') {
            $new_path = $directorio . '/' . $_FILES['documentos']['name'][$i];
            move_uploaded_file($tmp_path, $new_path);
        }
    }

    switch($_POST['payment_terms']){
        case 'Net 30':
            $payment_terms = 'Net 30';
            break;
        case 'Net 30/CC':
            $payment_terms = 'Net 30/CC';
            break;
    }

    $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
    $usuario_designado = $usuario->obtener_id();
    $cotizacion_editada = RepositorioRfq::actualizar_usuario_designado(Conexion::obtener_conexion(), $usuario_designado, $_POST['id_rfq']);
    $cotizacion_editada4 = RepositorioRfq::actualizar_rfq_inicio(Conexion::obtener_conexion(), $_POST['email_code'], $_POST['type_of_bid'], $_POST['issue_date'], $_POST['end_date'], $_POST['id_rfq']);
    $cotizacion_editada3 = RepositorioRfq::actualizar_shipping(Conexion::obtener_conexion(), htmlspecialchars($_POST['shipping']), $_POST['shipping_cost'], $_POST['id_rfq']);
    $cotizacion_editada1 = RepositorioRfq::actualizar_taxes_profit(Conexion::obtener_conexion(), $_POST['taxes'], $_POST['profit'], $_POST['total_cost'], $_POST['total_price'], $_POST['additional_general'], $_POST['id_rfq']);
    $cotizacion_editada2 = RepositorioRfq::actualizar_payment_terms(Conexion::obtener_conexion(), $payment_terms, $_POST['id_rfq']);
    $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
    if ($usuario_antiguo->obtener_nombre_usuario() != $_POST['usuario_designado']) {
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
    Conexion::cerrar_conexion();
//}
//if (isset($_POST['guardar_cambios_cotizacion2'])) {
    Conexion::abrir_conexion();
    $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
    RepositorioRfq::actualizar_rfq_2(Conexion::obtener_conexion(), $_POST['comments'], $_POST['ship_via'], htmlspecialchars($_POST['address']), htmlspecialchars($_POST['ship_to']), $_POST['id_rfq']);

    $expiration_date = $_POST['expiration_date'];
    $partes_expiration_date = explode('/', $expiration_date);
    $expiration_date = $partes_expiration_date[2] . '-' . $partes_expiration_date[0] . '-' . $partes_expiration_date[1];
    $expiration_date = strtotime($expiration_date);
    $expiration_date = date('Y-m-d', $expiration_date);

    $fecha_completado = $_POST['completed_date'];
    $partes_completed_date = explode('/', $fecha_completado);
    $fecha_completado = $partes_completed_date[2] . '-' . $partes_completed_date[0] . '-' . $partes_completed_date[1];
      $fecha_completado = strtotime($fecha_completado);
      $fecha_completado = date('Y-m-d', $fecha_completado);
      RepositorioRfq::actualizar_fecha_y_completado(Conexion::obtener_conexion(), $fecha_completado, $expiration_date, $_POST['id_rfq']);
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

    if (!$cotizacion_recuperada->obtener_completado()) {
        if (isset($_POST['completado']) && $_POST['completado'] == 'si') {
            $completado = 1;
        } else {
            $completado = 0;
        }
        if ($completado) {
          RepositorioRfq::check_completed(Conexion::obtener_conexion(), $_POST['id_rfq']);
            if ($cargo < 4) {
                Redireccion::redirigir1(COMPLETADOS . $canal);
            } else {
                Redireccion::redirigir1(COTIZACIONES . $canal);
            }
        } else {
            $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
        }
    } else if (!$cotizacion_recuperada->obtener_status()) {
        if (isset($_POST['status']) && $_POST['status'] == 'si') {
            $submitted = 1;
        } else {
            $submitted = 0;
        }
        if ($submitted) {
            RepositorioRfq::actualizar_fecha_y_submitted(Conexion::obtener_conexion(), $_POST['id_rfq']);
            Redireccion::redirigir1(COMPLETADOS . $canal);
        }else{
            $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
        }
    }else if(!$cotizacion_recuperada-> obtener_award()){
        if(isset($_POST['award']) && $_POST['award'] == 'si'){
            $award = 1;
        }else{
            $award = 0;
        }
        if($award){
            RepositorioRfq::actualizar_fecha_y_award(Conexion::obtener_conexion(), $_POST['id_rfq']);

            if($cargo < 4){
                Redireccion::redirigir1(AWARD . $canal);
            }else{
                Redireccion::redirigir1(COTIZACIONES . $canal);
            }
        }else{
            $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
        }
    }
    Conexion::cerrar_conexion();
}
?>
