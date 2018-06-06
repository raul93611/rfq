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
                include_once 'plantillas/editar_cotizacion.inc.php';
                break;
            case 'completados':
                include_once 'plantillas/completados.inc.php';
                break;
            case 'add_equipment':
                include_once 'plantillas/add_equipment.inc.php';
                break;
            default :
                include_once 'plantillas/cotizaciones.inc.php';
        }
        break;
}
?>
<?php
include_once 'plantillas/documento_cierre.inc.php';
?>

