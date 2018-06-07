<?php

include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/Redireccion.inc.php';

include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/Usuario.inc.php';
include_once 'app/ValidadorUsuario.inc.php';
include_once 'app/ValidadorLogin.inc.php';
include_once 'app/ValidadorRegistro.inc.php';

include_once 'app/Rfq.inc.php';
include_once 'app/RepositorioRfq.inc.php';
include_once 'app/ValidadorCotizacion.inc.php';
include_once 'app/ValidadorCotizacionRegistro.inc.php';
include_once 'app/ValidadorCotizacionEdicion.inc.php';

include_once 'app/Equipo.inc.php';
include_once 'app/RepositorioEquipo.inc.php';

$componentes_url = parse_url($_SERVER['REQUEST_URI']);
$ruta = $componentes_url['path'];

$partes_ruta = explode('/', $ruta);
$partes_ruta = array_filter($partes_ruta);
$partes_ruta = array_slice($partes_ruta, 0);
$ruta_elegida = 'vistas/404.php';

if ($partes_ruta[0] == 'rfq') {
    if (count($partes_ruta) == 1) {
        $ruta_elegida = 'vistas/home.php';
    } else if (count($partes_ruta) == 2) {
        switch ($partes_ruta[1]) {
            case 'perfil':
                $gestor_actual = '';
                $ruta_elegida = 'vistas/perfil.php';
                break;
            case 'genera_usuario':
                $ruta_elegida = 'herramientas/genera_usuario.php';
                break;
            case 'logout':
                $ruta_elegida = 'scripts/logout.php';
                break;
        }
    } else if (count($partes_ruta) == 3) {
        if ($partes_ruta[1] == 'perfil') {
            switch ($partes_ruta[2]) {
                case 'registro':
                    $gestor_actual = 'registro';
                    $ruta_elegida = 'vistas/perfil.php';
                    break;
                case 'registro_correcto':
                    $gestor_actual = 'registro_correcto';
                    $ruta_elegida = 'vistas/perfil.php';
                    break;
                case 'eliminar_usuario':
                    $gestor_actual = 'eliminar_usuario';
                    $ruta_elegida = 'scripts/eliminar_usuario.php';
                    break;
            }
        }else if($partes_ruta[1] == 'proposal'){
            $id_rfq = $partes_ruta[2];
            $ruta_elegida = 'scripts/proposal.php';
        }
    } else if (count($partes_ruta) == 4) {
        if ($partes_ruta[1] == 'perfil' && $partes_ruta[2] == 'cotizaciones') {
            $gestor_actual = 'cotizaciones';
            $ruta_elegida = 'vistas/perfil.php';
            switch ($partes_ruta[3]) {
                case 'gsa_buy':
                    $cotizacion = 'gsa_buy';
                    break;
                case 'fedbid':
                    $cotizacion = 'fedbid';
                    break;
                case 'emails':
                    $cotizacion = 'emails';
                    break;
                case 'findfrp':
                    $cotizacion = 'findfrp';
                    break;
                case 'embassies':
                    $cotizacion = 'embassies';
                    break;
                case 'fbo':
                    $cotizacion = 'fbo';
                    break;
                case 'nuevo':
                    $cotizacion = 'nuevo';
                    break;
                case 'registro_cotizacion_correcto':
                    $cotizacion = 'registro_cotizacion_correcto';
                    break;
                case 'editar_cotizacion':
                    $cotizacion = 'editar_cotizacion';
                    break;
                case 'completados':
                    $cotizacion = 'completados';
                    break;
                case 'add_equipment':
                    $cotizacion = 'add_equipment';
                    break;
            }
        } else if ($partes_ruta[1] == 'descarga') {
            $id_rfq = $partes_ruta[2];
            $archivo = $partes_ruta[3];
            $ruta_elegida = 'scripts/descargar.php';
        }
    }
}

include_once $ruta_elegida;
?>
