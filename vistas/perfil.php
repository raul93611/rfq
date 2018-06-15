<?php

if (!ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir1(SERVIDOR);
}

$titulo = 'Perfil';

Conexion::abrir_conexion();
$usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $_SESSION['id_usuario']);
Conexion::cerrar_conexion();
$cargo = $usuario->obtener_cargo();

include_once 'plantillas/documento_declaracion.inc.php';
include_once 'plantillas/navbar.inc.php';
include_once 'plantillas/barra_lateral.inc.php';
?>

<?php

switch ($gestor_actual) {
    case '':
        include_once 'plantillas/muro.inc.php';
        break;
    case 'registro':
        if ($cargo == 1) {
            include_once 'plantillas/registro.inc.php';
        } else {
            include_once 'plantillas/muro.inc.php';
        }
        break;
    case 'registro_correcto':
        include_once 'plantillas/registro_correcto.inc.php';
        break;
    case 'cotizaciones':
        switch ($cotizacion) {
            case 'nuevo':
                if ($cargo <= 3) {
                    include_once 'plantillas/nueva_cotizacion.inc.php';
                } else {
                    Redireccion::redirigir1(PERFIL);
                }
                break;
            case 'registro_cotizacion_correcto':
                include_once 'plantillas/registro_cotizacion_correcto.inc.php';
                break;
            case 'editar_cotizacion':
                Conexion::abrir_conexion();
                $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
                Conexion::cerrar_conexion();
                include_once 'plantillas/editar_cotizacion.inc.php';
                break;
            case 'add_item':
                Conexion::abrir_conexion();
                $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
                Conexion::cerrar_conexion();
                include_once 'plantillas/add_item.inc.php';
                break;
            case 'add_provider':
                Conexion::abrir_conexion();
                $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
                Conexion::cerrar_conexion();
                include_once 'plantillas/add_provider.inc.php';
                break;
            case 'edit_item':
                Conexion::abrir_conexion();
                $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
                Conexion::cerrar_conexion();
                include_once 'plantillas/edit_item.inc.php';
                break;
            case 'edit_provider':
                Conexion::abrir_conexion();
                $provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $id_provider);
                $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider-> obtener_id_item());
                Conexion::cerrar_conexion();
                include_once 'plantillas/edit_provider.inc.php';
                break;
            default :
                include_once 'plantillas/cotizaciones.inc.php';
        }
        break;
    case 'completados':
        include_once 'plantillas/completados.inc.php';
        break;
}
?>
<?php

include_once 'plantillas/documento_cierre.inc.php';
?>

