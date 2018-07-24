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

include_once 'app/Item.inc.php';
include_once 'app/RepositorioItem.inc.php';

include_once 'app/Provider.inc.php';
include_once 'app/RepositorioProvider.inc.php';

include_once 'app/Cuestionario.inc.php';
include_once 'app/RepositorioCuestionario.inc.php';

include_once 'app/HighLevelRequirement.inc.php';
include_once 'app/RepositorioHighLevelRequirement.inc.php';

include_once 'app/OutOfScope.inc.php';
include_once 'app/RepositorioOutOfScope.inc.php';

include_once 'app/ProjectRisk.inc.php';
include_once 'app/RepositorioProjectRisk.inc.php';

include_once 'app/ProjectMilestone.inc.php';
include_once 'app/RepositorioProjectMilestone.inc.php';

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
        } else if ($partes_ruta[1] == 'proposal') {
            $id_rfq = $partes_ruta[2];
            $ruta_elegida = 'scripts/proposal.php';
        }else if($partes_ruta[1] == 'proposal_gsa'){
            $id_rfq = $partes_ruta[2];
            $encabezado = 1;
            $ruta_elegida = 'scripts/proposal.php';
        }
    } else if (count($partes_ruta) == 4) {
      if($partes_ruta[1] == 'delete_document'){
        $id_rfq = $partes_ruta[2];
        $archivo = $partes_ruta[3];
        $ruta_elegida = 'scripts/delete_document.php';
      }else if ($partes_ruta[1] == 'perfil' && $partes_ruta[2] == 'cotizaciones') {
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
                case 'no_bid':
                    $cotizacion = 'no_bid';
                    break;
            }
        } else if ($partes_ruta[1] == 'perfil' && $partes_ruta[2] == 'completados') {
            $gestor_actual = 'completados';
            $ruta_elegida = 'vistas/perfil.php';
            switch ($partes_ruta[3]) {
                case 'gsa_buy':
                    $cotizacion = 'gsa_buy_completados';
                    break;
                case 'fedbid':
                    $cotizacion = 'fedbid_completados';
                    break;
                case 'emails':
                    $cotizacion = 'emails_completados';
                    break;
                case 'findfrp':
                    $cotizacion = 'findfrp_completados';
                    break;
                case 'embassies':
                    $cotizacion = 'embassies_completados';
                    break;
                case 'fbo':
                    $cotizacion = 'fbo_completados';
                    break;
            }
        }else if($partes_ruta[1] == 'perfil' && $partes_ruta[2] == 'submitted'){
            $gestor_actual = 'submitted';
            $ruta_elegida = 'vistas/perfil.php';
            switch ($partes_ruta[3]){
                case 'gsa_buy':
                    $cotizacion = 'gsa_buy_submitted';
                    break;
                case 'fedbid':
                    $cotizacion = 'fedbid_submitted';
                    break;
                case 'emails':
                    $cotizacion = 'emails_submitted';
                    break;
                case 'findfrp':
                    $cotizacion = 'findfrp_submitted';
                    break;
                case 'embassies':
                    $cotizacion = 'embassies_submitted';
                    break;
                case 'fbo':
                    $cotizacion = 'fbo_submitted';
                    break;
            }
        }else if ($partes_ruta[1] == 'perfil' && $partes_ruta[2] == 'award') {
            $gestor_actual = 'award';
            $ruta_elegida = 'vistas/perfil.php';
            switch ($partes_ruta[3]){
                case 'gsa_buy':
                    $cotizacion = 'gsa_buy_award';
                    break;
                case 'fedbid':
                    $cotizacion = 'fedbid_award';
                    break;
                case 'emails':
                    $cotizacion = 'emails_award';
                    break;
                case 'findfrp':
                    $cotizacion = 'findfrp_award';
                    break;
                case 'embassies':
                    $cotizacion = 'embassies_award';
                    break;
                case 'fbo':
                    $cotizacion = 'fbo_award';
                    break;
            }
        }
    } else if (count($partes_ruta) == 5) {
        if ($partes_ruta[1] == 'perfil' && $partes_ruta[2] == 'cotizaciones') {
            $gestor_actual = 'cotizaciones';
            $ruta_elegida = 'vistas/perfil.php';
            switch ($partes_ruta[3]) {
                case 'editar_cotizacion':
                    $cotizacion = 'editar_cotizacion';
                    $id_rfq = $partes_ruta[4];
                    break;
                case 'add_item':
                    $cotizacion = 'add_item';
                    $id_rfq = $partes_ruta[4];
                    break;
                case 'add_provider':
                    $cotizacion = 'add_provider';
                    $id_item = $partes_ruta[4];
                    break;
                case 'edit_item':
                    $cotizacion = 'edit_item';
                    $id_item = $partes_ruta[4];
                    break;
                case 'delete_item':
                    $cotizacion = 'delete_item';
                    $id_item = $partes_ruta[4];
                    break;
                case 'edit_provider':
                    $cotizacion = 'edit_provider';
                    $id_provider = $partes_ruta[4];
                    break;
                case 'delete_provider':
                    $cotizacion = 'delete_provider';
                    $id_provider = $partes_ruta[4];
                    break;
                case 'cuestionario':
                    $cotizacion = 'cuestionario';
                    $id_rfq = $partes_ruta[4];
                    break;
                case 'add_high_level_requirement':
                    $cotizacion = 'add_high_level_requirement';
                    $id_cuestionario = $partes_ruta[4];
                    break;
                case 'add_out_of_scope':
                    $cotizacion = 'add_out_of_scope';
                    $id_cuestionario = $partes_ruta[4];
                    break;
                case 'add_project_risk':
                    $cotizacion = 'add_project_risk';
                    $id_cuestionario = $partes_ruta[4];
                    break;
                case 'add_project_milestone':
                    $cotizacion = 'add_project_milestone';
                    $id_cuestionario = $partes_ruta[4];
                    break;
                case 'edit_high_level_requirement':
                    $cotizacion = 'edit_high_level_requirement';
                    $id_high_level_requirement = $partes_ruta[4];
                    break;
                case 'edit_out_of_scope':
                    $cotizacion = 'edit_out_of_scope';
                    $id_out_of_scope = $partes_ruta[4];
                    break;
                case 'edit_project_risk':
                    $cotizacion = 'edit_project_risk';
                    $id_project_risk = $partes_ruta[4];
                    break;
                case 'edit_project_milestone':
                    $cotizacion = 'edit_project_milestone';
                    $id_project_milestone = $partes_ruta[4];
                    break;
            }
        }
    }
}

include_once $ruta_elegida;
?>
