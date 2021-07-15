<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
session_save_path('temp');
session_start();
include_once 'app/Bootstrap/config.inc.php';
include_once 'app/Bootstrap/Conexion.inc.php';
include_once 'app/Bootstrap/ControlSesion.inc.php';
include_once 'app/Bootstrap/Redireccion.inc.php';

include_once 'app/User/RepositorioUsuario.inc.php';
include_once 'app/User/Usuario.inc.php';
include_once 'app/User/ValidadorUsuario.inc.php';
include_once 'app/User/ValidadorLogin.inc.php';
include_once 'app/User/ValidadorRegistro.inc.php';

include_once 'app/Cuestionario/Cuestionario.inc.php';
include_once 'app/Cuestionario/RepositorioCuestionario.inc.php';

include_once 'app/Quote/Rfq.inc.php';
include_once 'app/Quote/RepositorioRfq.inc.php';
include_once 'app/Quote/ValidadorCotizacion.inc.php';
include_once 'app/Quote/ValidadorCotizacionRegistro.inc.php';

include_once 'app/Quote/Item.inc.php';
include_once 'app/Quote/RepositorioItem.inc.php';

include_once 'app/Quote/Provider.inc.php';
include_once 'app/Quote/RepositorioProvider.inc.php';

include_once 'app/Quote/Subitem.inc.php';
include_once 'app/Quote/RepositorioSubitem.inc.php';

include_once 'app/Quote/ProviderSubitem.inc.php';
include_once 'app/Quote/RepositorioProviderSubitem.inc.php';

include_once 'app/Comment/Comment.inc.php';
include_once 'app/Comment/RepositorioComment.inc.php';

include_once 'app/ReQuote/ReQuote.inc.php';
include_once 'app/ReQuote/ReQuoteRepository.inc.php';

include_once 'app/ReQuote/ReQuoteItem.inc.php';
include_once 'app/ReQuote/ReQuoteItemRepository.inc.php';

include_once 'app/ReQuote/ReQuoteProvider.inc.php';
include_once 'app/ReQuote/ReQuoteProviderRepository.inc.php';

include_once 'app/ReQuote/ReQuoteSubitem.inc.php';
include_once 'app/ReQuote/ReQuoteSubitemRepository.inc.php';

include_once 'app/ReQuote/ReQuoteSubitemProvider.inc.php';
include_once 'app/ReQuote/ReQuoteSubitemProviderRepository.inc.php';

include_once 'app/Utilities/ProposalRepository.inc.php';
include_once 'app/Utilities/ExcelRepository.inc.php';

include_once 'app/Utilities/Input.inc.php';
include_once 'app/Utilities/Email.inc.php';

include_once 'app/Quote/AuditTrail.inc.php';
include_once 'app/Quote/AuditTrailRepository.inc.php';

include_once 'app/ReQuote/ReQuoteAuditTrail.inc.php';
include_once 'app/ReQuote/ReQuoteAuditTrailRepository.inc.php';

include_once 'app/TypeOfBid/TypeOfBid.inc.php';
include_once 'app/TypeOfBid/TypeOfBidRepository.inc.php';

include_once 'app/Service/Service.inc.php';
include_once 'app/Service/ServiceRepository.inc.php';

include_once 'app/Tracking/Tracking.inc.php';
include_once 'app/Tracking/TrackingRepository.inc.php';

include_once 'app/Tracking/TrackingSubitem.inc.php';
include_once 'app/Tracking/TrackingSubitemRepository.inc.php';

include_once 'app/Fulfillment/FulfillmentRepository.inc.php';

include_once 'app/Fulfillment/FulfillmentItem.inc.php';
include_once 'app/Fulfillment/FulfillmentItemRepository.inc.php';

include_once 'app/Fulfillment/FulfillmentSubitem.inc.php';
include_once 'app/Fulfillment/FulfillmentSubitemRepository.inc.php';

include_once 'app/Fulfillment/FulfillmentService.inc.php';
include_once 'app/Fulfillment/FulfillmentServiceRepository.inc.php';

include_once 'app/ProviderList/ProviderList.inc.php';
include_once 'app/ProviderList/ProviderListRepository.inc.php';

include_once 'app/PaymentTerm/PaymentTerm.inc.php';
include_once 'app/PaymentTerm/PaymentTermRepository.inc.php';

include_once 'app/Report/Report.inc.php';

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
      case 'main_charts':
        $ruta_elegida = 'scripts/utilities/main_charts.php';
        break;
      case 'logout':
        $ruta_elegida = 'scripts/user/logout.php';
        break;
      case 'pdf_report':
        $gestor_actual = 'pdf_report';
        $ruta_elegida = 'scripts/pdf_report.php';
        break;
      case 'guardar_comment':
        $ruta_elegida = 'scripts/utilities/guardar_comment.php';
        break;
      case 'recover_password_form':
        $ruta_elegida = 'herramientas/recover_password_form.php';
        break;
      case 'save_re_quote_item':
        $ruta_elegida = 'scripts/re_quote/save_re_quote_item.php';
        break;
      case 'save_edit_re_quote_item':
        $ruta_elegida = 'scripts/re_quote/save_edit_re_quote_item.php';
        break;
      case 'save_re_quote_provider':
        $ruta_elegida = 'scripts/re_quote/save_re_quote_provider.php';
        break;
      case 'save_edit_re_quote_provider':
        $ruta_elegida = 'scripts/re_quote/save_edit_re_quote_provider.php';
        break;
      case 'save_re_quote_subitem':
        $ruta_elegida = 'scripts/re_quote/save_re_quote_subitem.php';
        break;
      case 'save_edit_re_quote_subitem':
        $ruta_elegida = 'scripts/re_quote/save_edit_re_quote_subitem.php';
        break;
      case 'save_re_quote_subitem_provider':
        $ruta_elegida = 'scripts/re_quote/save_re_quote_subitem_provider.php';
        break;
      case 'save_edit_re_quote_subitem_provider':
        $ruta_elegida = 'scripts/re_quote/save_edit_re_quote_subitem_provider.php';
        break;
      case 'save_re_quote':
        $ruta_elegida = 'scripts/re_quote/save_re_quote.php';
        break;
      case 'save_quote_info':
        $ruta_elegida = 'scripts/quote/save_quote_info.php';
        break;
      case 'add_service':
        $ruta_elegida = 'scripts/service/add_service.php';
        break;
      case 'edit_service';
        $ruta_elegida = 'scripts/service/edit_service.php';
        break;
      case 'save_tracking':
        $ruta_elegida = 'scripts/tracking/save_tracking.php';
        break;
      case 'save_tracking_subitem':
        $ruta_elegida = 'scripts/tracking/save_tracking_subitem.php';
        break;
      case 'save_edit_tracking':
        $ruta_elegida = 'scripts/tracking/save_edit_tracking.php';
        break;
      case 'save_edit_tracking_subitem':
        $ruta_elegida = 'scripts/tracking/save_edit_tracking_subitem.php';
        break;
      case 'save_fulfillment_item':
        $ruta_elegida = 'scripts/fulfillment/save_fulfillment_item.php';
        break;
      case 'save_edit_fulfillment_item':
        $ruta_elegida = 'scripts/fulfillment/save_edit_fulfillment_item.php';
        break;
      case 'delete_fulfillment_item':
        $ruta_elegida = 'scripts/fulfillment/delete_fulfillment_item.php';
        break;
      case 'save_fulfillment_subitem':
        $ruta_elegida = 'scripts/fulfillment/save_fulfillment_subitem.php';
        break;
      case 'save_edit_fulfillment_subitem':
        $ruta_elegida = 'scripts/fulfillment/save_edit_fulfillment_subitem.php';
        break;
      case 'delete_fulfillment_subitem':
        $ruta_elegida = 'scripts/fulfillment/delete_fulfillment_subitem.php';
        break;
      case 'save_fulfillment_service':
        $ruta_elegida = 'scripts/fulfillment/save_fulfillment_service.php';
        break;
      case 'save_edit_fulfillment_service':
        $ruta_elegida = 'scripts/fulfillment/save_edit_fulfillment_service.php';
        break;
      case 'delete_fulfillment_service':
        $ruta_elegida = 'scripts/fulfillment/delete_fulfillment_service.php';
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
        case 'reports':
          $gestor_actual = 'reports';
          break;
        case 'fulfillment_quotes':
          $gestor_actual = 'fulfillment_quotes';
          break;
        case 'invoice_quotes':
          $gestor_actual = 'invoice_quotes';
          break;
        case 'no_bid':
          $gestor_actual = 'no_bid';
          break;
        case 'no_submitted':
          $gestor_actual = 'no_submitted';
          break;
        case 'cancelled':
          $gestor_actual = 'cancelled';
          break;
      }
      break;
      case 'proposal':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/utilities/proposal.php';
        break;
      case 'get_quote_files':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/utilities/get_quote_files.php';
        break;
      case 'proposal_gsa':
        $id_rfq = $partes_ruta[2];
        $encabezado = 1;
        $ruta_elegida = 'scripts/utilities/proposal.php';
        break;
      case 'copy_quote':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/copy_quote.php';
        break;
      case 'delete_quote':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/delete_quote.php';
        break;
      case 'guardar_editar_cotizacion':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/guardar_editar_cotizacion.php';
        break;
      case 'guardar_add_item':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/guardar_add_item.php';
        break;
      case 'guardar_add_provider':
        $id_item = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/guardar_add_provider.php';
        break;
      case 'guardar_edit_item':
        $id_item = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/guardar_edit_item.php';
        break;
      case 'delete_item':
        $id_item = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/delete_item.php';
        break;
      case 'guardar_edit_provider':
        $id_provider = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/guardar_edit_provider.php';
        break;
      case 'guardar_add_subitem':
        $id_item = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/guardar_add_subitem.php';
        break;
      case 'guardar_add_provider_subitem':
        $id_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/guardar_add_provider_subitem.php';
        break;
      case 'guardar_edit_subitem':
        $id_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/guardar_edit_subitem.php';
        break;
      case 'guardar_edit_provider_subitem':
        $id_provider_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/guardar_edit_provider_subitem.php';
        break;
      case 'delete_provider':
        $id_provider = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/delete_provider.php';
        break;
      case 'delete_provider_subitem':
        $id_provider_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/delete_provider_subitem.php';
        break;
      case 'delete_subitem':
        $id_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/delete_subitem.php';
        break;
      case 'enable_user':
        $id_usuario = $partes_ruta[2];
        $ruta_elegida = 'scripts/user/enable_user.php';
        break;
      case 'disable_user':
        $id_usuario = $partes_ruta[2];
        $ruta_elegida = 'scripts/user/disable_user.php';
        break;
      case 'create_project':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/create_project.php';
        break;
      case 'pdf_tabla_items';
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/utilities/pdf_tabla_items.php';
        break;
      case 'excel_items_table';
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/utilities/excel_items_table.php';
        break;
      case 'pdf_re_quote';
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/utilities/pdf_re_quote.php';
        break;
      case 'load_img':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/utilities/load_img.php';
        break;
      case 'restart_password':
        $url_secreta = $partes_ruta[2];
        $ruta_elegida = 'herramientas/restart_password.php';
        break;
      case 'delete_re_quote_provider':
        $ruta_elegida = 'scripts/re_quote/delete_re_quote_provider.php';
        $id_re_quote_provider = $partes_ruta[2];
        break;
      case 'delete_re_quote_subitem_provider':
        $ruta_elegida = 'scripts/re_quote/delete_re_quote_subitem_provider.php';
        $id_re_quote_subitem_provider = $partes_ruta[2];
        break;
      case 'delete_re_quote_subitem':
        $ruta_elegida = 'scripts/re_quote/delete_re_quote_subitem.php';
        $id_re_quote_subitem = $partes_ruta[2];
        break;
      case 'delete_re_quote_item':
        $ruta_elegida = 'scripts/re_quote/delete_re_quote_item.php';
        $id_re_quote_item = $partes_ruta[2];
        break;
      case 'remove_award':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/remove_award.php';
        break;
      case 'remove_fulfillment':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/quote/remove_fulfillment.php';
        break;
      case 'reload_requote':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/re_quote/reload_requote.php';
        break;
      case 'load_service';
        $id_service = $partes_ruta[2];
        $ruta_elegida = 'scripts/service/load_service.php';
        break;
      case 'delete_service';
        $id_service = $partes_ruta[2];
        $ruta_elegida = 'scripts/service/delete_service.php';
        break;
      case 'delete_tracking':
        $id_tracking = $partes_ruta[2];
        $ruta_elegida = 'scripts/tracking/delete_tracking.php';
        break;
      case 'delete_tracking_subitem':
        $id_tracking_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/tracking/delete_tracking_subitem.php';
        break;
      case 'tracking_pdf':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/tracking/tracking_pdf.php';
        break;
      case 'load_tracking':
        $id_tracking = $partes_ruta[2];
        $ruta_elegida = 'scripts/tracking/load_tracking.php';
        break;
      case 'load_tracking_box':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/tracking/load_tracking_box.php';
        break;
      case 'load_tracking_subitem':
        $id_tracking_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/tracking/load_tracking_subitem.php';
        break;
      case 'load_fulfillment_page':
        $id_rfq = $partes_ruta[2];
        $ruta_elegida = 'scripts/fulfillment/load_fulfillment_page.php';
        break;
      case 'load_fulfillment_item':
        $id_fulfillment_item = $partes_ruta[2];
        $ruta_elegida = 'scripts/fulfillment/load_fulfillment_item.php';
        break;
      case 'load_fulfillment_subitem':
        $id_fulfillment_subitem = $partes_ruta[2];
        $ruta_elegida = 'scripts/fulfillment/load_fulfillment_subitem.php';
        break;
      case 'load_fulfillment_service':
        $id_fulfillment_service = $partes_ruta[2];
        $ruta_elegida = 'scripts/fulfillment/load_fulfillment_service.php';
        break;
      default:
      break;
    }
  } else if (count($partes_ruta) == 4) {
    if($partes_ruta[1] == 'delete_document'){
      $id_rfq = $partes_ruta[2];
      $archivo = $partes_ruta[3];
      $ruta_elegida = 'scripts/utilities/delete_document.php';
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
        case 're_quote':
          $gestor_actual = 're_quote';
          $id_rfq = $partes_ruta[3];
          break;
        case 'tracking':
          $gestor_actual = 'tracking';
          $id_rfq = $partes_ruta[3];
          break;
        case 'fulfillment':
          $gestor_actual = 'fulfillment';
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
