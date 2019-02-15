<?php
session_save_path('temp');
session_start();
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/Redireccion.inc.php';

include_once '../rfp/app/Connection.inc.php';

include_once 'app/RfpComment.inc.php';
include_once '../rfp/app/CommentRepository.inc.php';

include_once '../rfp/app/User.inc.php';
include_once '../rfp/app/UserRepository.inc.php';

include_once '../rfp/app/Project.inc.php';
include_once '../rfp/app/ProjectRepository.inc.php';

include_once '../rfp/app/Service.inc.php';
include_once '../rfp/app/ServiceRepository.inc.php';

include_once '../rfp/app/ContactList.inc.php';
include_once '../rfp/app/ContactListRepository.inc.php';

include_once '../fullfillment/app/ConnectionFullFillment.inc.php';
include_once '../fullfillment/app/RepositorioRfqFullFillment.inc.php';
include_once '../fullfillment/app/RepositorioItemFullFillment.inc.php';
include_once '../fullfillment/app/RepositorioProviderFullFillment.inc.php';
include_once '../fullfillment/app/RepositorioSubitemFullFillment.inc.php';
include_once '../fullfillment/app/RepositorioProviderSubitemFullFillment.inc.php';
include_once '../fullfillment/app/CommentRfqFullFillment.inc.php';
include_once '../fullfillment/app/RepositorioRfqFullFillmentComment.inc.php';
include_once '../fullfillment/app/RfqFullFillmentPart.inc.php';
include_once '../fullfillment/app/RfqFullFillmentPartRepository.inc.php';
include_once '../fullfillment/app/UserFullFillmentRepository.inc.php';
include_once '../fullfillment/app/ExtraItem.inc.php';
include_once '../fullfillment/app/ExtraSubitem.inc.php';
include_once '../fullfillment/app/ExtraItemRepository.inc.php';
include_once '../fullfillment/app/ExtraSubitemRepository.inc.php';

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

include_once 'app/Subitem.inc.php';
include_once 'app/RepositorioSubitem.inc.php';

include_once 'app/ProviderSubitem.inc.php';
include_once 'app/RepositorioProviderSubitem.inc.php';

include_once 'app/Comment.inc.php';
include_once 'app/RepositorioComment.inc.php';

include_once 'app/ReQuote.inc.php';
include_once 'app/ReQuoteRepository.inc.php';

include_once 'app/ReQuoteItem.inc.php';
include_once 'app/ReQuoteItemRepository.inc.php';

include_once 'app/ReQuoteProvider.inc.php';
include_once 'app/ReQuoteProviderRepository.inc.php';

include_once 'app/ReQuoteSubitem.inc.php';
include_once 'app/ReQuoteSubitemRepository.inc.php';

include_once 'app/ReQuoteSubitemProvider.inc.php';
include_once 'app/ReQuoteSubitemProviderRepository.inc.php';

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
      case 'pdf_report':
        $gestor_actual = 'pdf_report';
        $ruta_elegida = 'scripts/pdf_report.php';
        break;
      case 'guardar_comment':
        $ruta_elegida = 'scripts/guardar_comment.php';
        break;
      case 'guardar_fullfillment_form':
        $ruta_elegida = 'scripts/guardar_fullfillment_form.php';
        break;
      case 'recover_password_form':
        $ruta_elegida = 'herramientas/recover_password_form.php';
        break;
      case 'save_re_quote_item':
        $ruta_elegida = 'scripts/save_re_quote_item.php';
        break;
      case 'save_edit_re_quote_item':
        $ruta_elegida = 'scripts/save_edit_re_quote_item.php';
        break;
      case 'save_re_quote_provider':
        $ruta_elegida = 'scripts/save_re_quote_provider.php';
        break;
      case 'save_edit_re_quote_provider':
        $ruta_elegida = 'scripts/save_edit_re_quote_provider.inc.php';
        break;
      case 'save_re_quote_subitem':
        $ruta_elegida = 'scripts/save_re_quote_subitem.php';
        break;
      case 'save_edit_re_quote_subitem':
        $ruta_elegida = 'scripts/save_edit_re_quote_subitem.php';
        break;
      case 'save_re_quote_subitem_provider':
        $ruta_elegida = 'scripts/save_re_quote_subitem_provider.php';
        break;
      case 'save_edit_re_quote_subitem_provider':
        $ruta_elegida = 'scripts/save_edit_re_quote_subitem_provider.php';
        break;
      case 'save_re_quote':
        $ruta_elegida = 'scripts/save_re_quote.php';
        break;
      case 'generate_excel_report':
        $ruta_elegida = 'scripts/generate_excel_report.php';
        break;
    }
  } else if (count($partes_ruta) == 3) {
    switch ($partes_ruta[1]) {
      case 'perfil':
      $ruta_elegida = 'vistas/perfil.php';
      switch ($partes_ruta[2]) {
        case 'registro':
          $gestor_actual = 'registro';
          break;
        case 'search_quotes':
          $gestor_actual = 'search_quotes';
          break;
        case 'employee_docs_page':
          $gestor_actual = 'employee_docs_page';
          break;
        case 'excel_reports':
          $gestor_actual = 'excel_reports';
          break;
        case 'fulfillment_quotes':
          $gestor_actual = 'fulfillment_quotes';
          break;
      }
      break;
      case 'proposal':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/proposal.php';
        break;
      case 'proposal_gsa':
        $id_rfq = $partes_ruta[2];
        $encabezado = 1;
        $ruta_elegida = 'scripts/proposal.php';
        break;
      case 'copy_quote':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/copy_quote.php';
        break;
      case 'delete_quote':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/delete_quote.php';
        break;
      case 'guardar_editar_cotizacion':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_editar_cotizacion.php';
        break;
      case 'guardar_add_item':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_add_item.php';
        break;
      case 'guardar_add_provider':
        $id_item = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_add_provider.php';
        break;
      case 'guardar_edit_item':
        $id_item = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_edit_item.php';
        break;
      case 'delete_item':
        $id_item = $partes_ruta[2];
        $ruta_elegida = 'scripts/delete_item.php';
        break;
      case 'guardar_edit_provider':
        $id_provider = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_edit_provider.php';
        break;
      case 'guardar_add_subitem':
        $id_item = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_add_subitem.php';
        break;
      case 'guardar_add_provider_subitem':
        $id_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_add_provider_subitem.php';
        break;
      case 'guardar_edit_subitem':
        $id_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_edit_subitem.php';
        break;
      case 'guardar_edit_provider_subitem':
        $id_provider_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_edit_provider_subitem.php';
        break;
      case 'delete_provider':
        $id_provider = $partes_ruta[2];
        $ruta_elegida = 'scripts/delete_provider.php';
        break;
      case 'delete_provider_subitem':
        $id_provider_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/delete_provider_subitem.php';
        break;
      case 'delete_subitem':
        $id_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/delete_subitem.php';
        break;
      case 'guardar_cuestionario':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_cuestionario.php';
        break;
      case 'guardar_add_high_level_requirement':
        $id_cuestionario = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_add_high_level_requirement.php';
        break;
      case 'guardar_add_out_of_scope':
        $id_cuestionario = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_add_out_of_scope.php';
        break;
      case 'guardar_add_project_risk':
        $id_cuestionario = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_add_project_risk.php';
        break;
      case 'guardar_add_project_milestone':
        $id_cuestionario = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_add_project_milestone.php';
        break;
      case 'guardar_edit_high_level_requirement':
        $id_cuestionario = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_edit_high_level_requirement.php';
        break;
      case 'guardar_edit_out_of_scope':
        $id_cuestionario = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_edit_out_of_scope.php';
        break;
      case 'guardar_edit_project_risk':
        $id_cuestionario = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_edit_project_risk.php';
        break;
      case 'guardar_edit_project_milestone':
        $id_cuestionario = $partes_ruta[2];
        $ruta_elegida = 'scripts/guardar_edit_project_milestone.php';
        break;
      case 'pdf_project_charter':
        $id_cuestionario = $partes_ruta[2];
        $ruta_elegida = 'scripts/pdf_project_charter.php';
        break;
      case 'enable_user':
        $id_usuario = $partes_ruta[2];
        $ruta_elegida = 'scripts/enable_user.php';
        break;
      case 'disable_user':
        $id_usuario = $partes_ruta[2];
        $ruta_elegida = 'scripts/disable_user.php';
        break;
      case 'create_project':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/create_project.php';
        break;
      case 'pdf_tabla_items';
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/pdf_tabla_items.php';
        break;
      case 'load_img':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/load_img.php';
        break;
      case 'restart_password':
        $url_secreta = $partes_ruta[2];
        $ruta_elegida = 'herramientas/restart_password.php';
        break;
      case 'delete_re_quote_provider':
        $ruta_elegida = 'scripts/delete_re_quote_provider.php';
        $id_re_quote_provider = $partes_ruta[2];
        break;
      case 'delete_re_quote_subitem_provider':
        $ruta_elegida = 'scripts/delete_re_quote_subitem_provider.php';
        $id_re_quote_subitem_provider = $partes_ruta[2];
        break;
      case 'delete_re_quote_subitem':
        $ruta_elegida = 'scripts/delete_re_quote_subitem.php';
        $id_re_quote_subitem = $partes_ruta[2];
        break;
      case 'delete_re_quote_item':
        $ruta_elegida = 'scripts/delete_re_quote_item.php';
        $id_re_quote_item = $partes_ruta[2];
        break;
      default:
      break;
    }
  } else if (count($partes_ruta) == 4) {
    if($partes_ruta[1] == 'delete_document'){
      $id_rfq = $partes_ruta[2];
      $archivo = $partes_ruta[3];
      $ruta_elegida = 'scripts/delete_document.php';
    }else if($partes_ruta[1] == 'perfil'){
      $ruta_elegida = 'vistas/perfil.php';
      switch ($partes_ruta[2]) {
        case 'add_re_quote_item':
          $gestor_actual = 'add_re_quote_item';
          $id_re_quote = $partes_ruta[3];
          break;
        case 'edit_re_quote_item':
          $gestor_actual = 'edit_re_quote_item';
          $id_re_quote_item = $partes_ruta[3];
          break;
        case 'add_re_quote_provider':
          $gestor_actual = 'add_re_quote_provider';
          $id_re_quote_item = $partes_ruta[3];
          break;
        case 'edit_re_quote_provider':
          $gestor_actual = 'edit_re_quote_provider';
          $id_re_quote_provider = $partes_ruta[3];
          break;
        case 'add_re_quote_subitem':
          $gestor_actual = 'add_re_quote_subitem';
          $id_re_quote_item = $partes_ruta[3];
          break;
        case 'edit_re_quote_subitem':
          $gestor_actual = 'edit_re_quote_subitem';
          $id_re_quote_subitem = $partes_ruta[3];
          break;
        case 'add_re_quote_subitem_provider':
          $gestor_actual = 'add_re_quote_subitem_provider';
          $id_re_quote_subitem = $partes_ruta[3];
          break;
        case 'edit_re_quote_subitem_provider':
          $gestor_actual = 'edit_re_quote_subitem_provider';
          $id_re_quote_subitem_provider = $partes_ruta[3];
          break;
        case 'cotizaciones':
          $gestor_actual = 'cotizaciones';
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
            case 'mailbox':
              $cotizacion = 'mailbox';
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
            case 'chemonics':
              $cotizacion = 'chemonics';
              break;
            case 'ebay_amazon':
              $cotizacion = 'ebay_amazon';
              break;
            case 'nuevo':
              $cotizacion = 'nuevo';
              break;
            case 'no_bid':
              $cotizacion = 'no_bid';
              break;
            case 'no_submitted':
              $cotizacion = 'no_submitted';
              break;
            case 'rfp_quotes':
              $cotizacion = 'rfp_quotes';
              break;
            case 'cancelled':
              $cotizacion = 'cancelled';
              break;
          }
          break;
        case 'completados':
          $gestor_actual = 'completados';
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
            case 'mailbox':
              $cotizacion = 'mailbox_completados';
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
          break;
        case 'submitted':
          $gestor_actual = 'submitted';
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
            case 'mailbox':
              $cotizacion = 'mailbox_submitted';
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
          break;
        case 'award':
          $gestor_actual = 'award';
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
            case 'mailbox':
              $cotizacion = 'mailbox_award';
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
            case 'chemonics':
              $cotizacion = 'chemonics_award';
              break;
            case 'ebay_amazon':
              $cotizacion = 'ebay_amazon_award';
              break;
          }
          break;
        case 'edit_user':
          $id_user = $partes_ruta[3];
          $gestor_actual = 'edit_user';
          break;
        case 'historial_comments':
          $id_rfq = $partes_ruta[3];
          $gestor_actual = 'historial_comments';
          break;
        case 're_quote':
          $gestor_actual = 're_quote';
          $id_rfq = $partes_ruta[3];
          break;
        default:
          break;
      }
    }
  } else if (count($partes_ruta) == 5) {
    if ($partes_ruta[1] == 'perfil') {
      $ruta_elegida = 'vistas/perfil.php';
      switch ($partes_ruta[2]) {
        case 'cotizaciones':
          $gestor_actual = 'cotizaciones';
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
            case 'add_provider_subitem':
              $cotizacion = 'add_provider_subitem';
              $id_subitem = $partes_ruta[4];
              break;
            case 'add_subitem':
              $cotizacion = 'add_subitem';
              $id_item = $partes_ruta[4];
              break;
            case 'edit_item':
              $cotizacion = 'edit_item';
              $id_item = $partes_ruta[4];
              break;
            case 'edit_subitem':
              $cotizacion = 'edit_subitem';
              $id_subitem = $partes_ruta[4];
              break;
            case 'delete_subitem':
              $cotizacion = 'delete_subitem';
              $id_subitem = $partes_ruta[4];
              break;
            case 'edit_provider':
              $cotizacion = 'edit_provider';
              $id_provider = $partes_ruta[4];
              break;
            case 'edit_provider_subitem':
              $cotizacion = 'edit_provider_subitem';
              $id_provider_subitem = $partes_ruta[4];
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
          break;
        default:
          break;
      }
    }
  }
}
include_once $ruta_elegida;
?>
