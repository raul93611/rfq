<?php

if (isset($_POST['registrar_cotizacion'])) {
    Conexion::abrir_conexion();
    $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
    $usuario_designado = $usuario->obtener_id();

    $validador = new ValidadorCotizacionRegistro($_POST['email_code'], $_POST['issue_date'], $_POST['end_date']);

    if ($validador->registro_cotizacion_valida()) {
        $cotizacion = new Rfq('', $_SESSION['id_usuario'], $usuario_designado, $_POST['canal'], $validador->obtener_email_code(), $_POST['type_of_bid'], $validador->obtener_issue_date(), $validador->obtener_end_date(), 0, 0, 0, 0, '', 0, '');
        list($cotizacion_insertada, $id_rfq) = RepositorioRfq::insertar_cotizacion(Conexion::obtener_conexion(), $cotizacion);

        if ($cotizacion_insertada) {
            $directorio = $_SERVER['DOCUMENT_ROOT'] . '/prueba/documentos/' . $id_rfq;
            mkdir($directorio, 0777);
            $documentos = array_filter($_FILES['documentos']['name']);
            $total = count($documentos);
            for ($i = 0; $i < $total; $i++) {
                $tmp_path = $_FILES['documentos']['tmp_name'][$i];

                if ($tmp_path != '') {
                    $new_path = $directorio . '/' . $_FILES['documentos']['name'][$i];
                    move_uploaded_file($tmp_path, $new_path);
                }
            }
            Redireccion::redirigir1(REGISTRO_COTIZACION_CORRECTO);
        }
    }
    Conexion::cerrar_conexion();
}
?>

