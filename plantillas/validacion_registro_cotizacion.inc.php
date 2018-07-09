<?php

if (isset($_POST['registrar_cotizacion'])) {
    Conexion::abrir_conexion();
    $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario(Conexion::obtener_conexion(), $_POST['usuario_designado']);
    $usuario_designado = $usuario->obtener_id();

    $validador = new ValidadorCotizacionRegistro(Conexion::obtener_conexion(), $_POST['email_code'], $_POST['issue_date'], $_POST['end_date'], $_POST['type_of_bid'], $_POST['usuario_designado'], $_POST['canal']);

    if ($validador->registro_cotizacion_valida()) {
        $cotizacion = new Rfq('', $_SESSION['id_usuario'], $usuario_designado, $_POST['canal'], $validador->obtener_email_code(), $_POST['type_of_bid'], $validador->obtener_issue_date(), $validador->obtener_end_date(), 0, 0, 0, 0, '', 0, '', '', '', '', '', '', '', '', 0, 0, '', '', 0);
        list($cotizacion_insertada, $id_rfq) = RepositorioRfq::insertar_cotizacion(Conexion::obtener_conexion(), $cotizacion);
        $cuestionario = new Cuestionario('', $id_rfq, '', '', '', '', '', '', '', '', '');
        RepositorioCuestionario::insertar_cuestionario(Conexion::obtener_conexion(), $cuestionario);

        if ($cotizacion_insertada) {
            $directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
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
            switch($cotizacion-> obtener_canal()){
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
    Conexion::cerrar_conexion();
}
?>
