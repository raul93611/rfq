<?php

if (isset($_POST['guardar_cambios_cotizacion'])) {
    Conexion::abrir_conexion();
    if (isset($_POST['status']) && $_POST['status'] == 'si') {
        $status = 1;
    } else {
        $status = 0;
    }

    if (isset($_POST['completado']) && $_POST['completado'] == 'si') {
        $completado = 1;
    } else {
        $completado = 0;
    }

    if (isset($_POST['award']) && $_POST['award'] == 'si') {
        $award = 1;
    } else {
        $award = 0;
    }
    
    if($completado){
        $fecha_completado = date('Y-m-d');
    }else{
        $fecha_completado = '';
    }
    
    $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
    $usuario_designado = $usuario->obtener_id();
    $validador = new ValidadorCotizacionEdicion($_POST['amount']);

    if ($validador->obtener_error_amount() == '') {
        $cotizacion_editada = RepositorioRfq::actualizar_cotizacion(Conexion::obtener_conexion(), $_POST['id_rfq'], $usuario_designado, $status, $completado, $validador->obtener_amount(), $_POST['proposal'], $_POST['comments'], $award, $fecha_completado);
        if ($cotizacion_editada) {
            $directorio = $_SERVER['DOCUMENT_ROOT'] . '/prueba/documentos/' . $_POST['id_rfq'];
            $documentos = array_filter($_FILES['documentos']['name']);
            $total = count($documentos);
            for ($i = 0; $i < $total; $i++) {
                $tmp_path = $_FILES['documentos']['tmp_name'][$i];

                if ($tmp_path != '') {
                    $new_path = $directorio . '/' . $_FILES['documentos']['name'][$i];
                    move_uploaded_file($tmp_path, $new_path);
                }
            }
            Redireccion::redirigir1(COTIZACIONES . 'gsa_buy');
        }
    }
    Conexion::cerrar_conexion();
}
?>
