<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
spl_autoload_register(function ($class) {
  $subfolder = [
    'Bootstrap' => ['Conexion', 'ControlSesion', 'Redireccion'],
    'User' => ['RepositorioUsuario', 'Usuario', 'ValidadorUsuario', 'ValidadorLogin', 'ValidadorRegistro'],
    'Cuestionario' => ['Cuestionario', 'RepositorioCuestionario'],
    'Quote' => ['Rfq', 'RepositorioRfq', 'ValidadorCotizacion', 'ValidadorCotizacionRegistro', 'Item', 'RepositorioItem', 'Provider', 'RepositorioProvider', 'Subitem', 'RepositorioSubitem', 'ProviderSubitem', 'RepositorioProviderSubitem', 'AuditTrail', 'AuditTrailRepository'],
    'Comment' => ['Comment', 'RepositorioComment'],
    'ReQuote' => ['ReQuote', 'ReQuoteRepository', 'ReQuoteItem', 'ReQuoteItemRepository', 'ReQuoteProvider', 'ReQuoteProviderRepository', 'ReQuoteSubitem', 'ReQuoteSubitemRepository', 'ReQuoteSubitemProvider', 'ReQuoteSubitemProviderRepository', 'ReQuoteAuditTrail', 'ReQuoteAuditTrailRepository', 'ReQuoteService', 'ReQuoteServiceRepository'],
    'Utilities' => ['PDFGenerator', 'ProposalRepository', 'ExcelRepository', 'Input', 'Email', 'TeamsIntegration'],
    'TypeOfBid' => ['TypeOfBid', 'TypeOfBidRepository'],
    'Service' => ['Service', 'ServiceRepository'],
    'Tracking' => ['Tracking', 'TrackingRepository', 'TrackingSubitem', 'TrackingSubitemRepository'],
    'Fulfillment' => ['FulfillmentRepository', 'FulfillmentItem', 'FulfillmentItemRepository', 'FulfillmentSubitem', 'FulfillmentSubitemRepository', 'FulfillmentService', 'FulfillmentServiceRepository', 'FulfillmentAuditTrailRepository'],
    'ProviderList' => ['ProviderList', 'ProviderListRepository'],
    'PaymentTerm' => ['PaymentTerm', 'PaymentTermRepository'],
    'Report' => ['Report'],
    'Task' => ['Task', 'TaskRepository'],
    'TaskComment' => ['TaskComment', 'TaskCommentRepository'],
    'TypeOfContract' => ['TypeOfContract', 'TypeOfContractRepository'],
    'SalesCommission' => ['SalesCommission', 'SalesCommissionRepository'],
    'Invoice' => ['Invoice', 'InvoiceRepository'],
    'Personnel' => ['Personnel', 'PersonnelRepository'],
    'CalendarEvent' => ['CalendarEvent', 'CalendarEventRepository'],
    'TypeOfProject' => ['TypeOfProject', 'TypeOfProjectRepository'],
    'YearlyProjection' => ['YearlyProjection', 'YearlyProjectionRepository'],
    'MonthlyProjection' => ['MonthlyProjection', 'MonthlyProjectionRepository'],
    'Room' => ['Room', 'RoomRepository']
  ];

  foreach ($subfolder as $key => $array) {
    if (in_array($class, $array)) {
      include_once "app/{$key}/{$class}.inc.php";
    }
  }
});

include_once 'app/Bootstrap/config.inc.php';
include_once 'app/Bootstrap/routes.inc.php';

session_start();

$componentes_url = parse_url($_SERVER['REQUEST_URI']);
$ruta = $componentes_url['path'];

$partes_ruta = explode('/', $ruta);
$partes_ruta = array_filter($partes_ruta);
$partes_ruta = array_slice($partes_ruta, 0);
$ruta_elegida = 'vistas/404.php';

switch ($partes_ruta[1] ?? null) {
  case '':
    $ruta_elegida = 'vistas/home.php';
    break;
  case 'main_charts':
    $ruta_elegida = 'scripts/utilities/main_charts.php';
    break;
  case 'reports_charts':
    $ruta_elegida = 'scripts/utilities/reports_charts.php';
    break;
  case 'perfil':
    $ruta_elegida = isset($_POST['generate_excel_report']) ? $ruta_elegida = 'scripts/utilities/excel_report_' . $_POST['report'] . '.php' :  'vistas/perfil.php';
    break;
  case 'logout':
    $ruta_elegida = 'scripts/user/logout.php';
    break;
  case 'internal_script':
    $ruta_elegida = 'scripts/utilities/internal_script.php';
    break;
  case 'employee_docs':
    switch ($partes_ruta[2]) {
      case 'upload':
        $ruta_elegida = 'scripts/utilities/employee_docs_upload.php';
        break;
      case 'container':
        $ruta_elegida = 'scripts/utilities/employee_docs_container.php';
        break;
      case 'delete':
        $ruta_elegida = 'scripts/utilities/employee_docs_delete.php';
        break;
    }
  case 'quote':
    switch ($partes_ruta[2]) {
      case 'remove_slave':
        $ruta_elegida = 'scripts/quote/remove_slave.php';
        break;
      case 'remove_master':
        $ruta_elegida = 'scripts/quote/remove_master.php';
        break;
      case 'guardar_comment':
        $ruta_elegida = 'scripts/utilities/guardar_comment.php';
        break;
      case 'save_checklist':
        $ruta_elegida = 'scripts/quote/save_checklist.php';
        break;
      case 'save_information':
        $ruta_elegida = 'scripts/quote/save_information.php';
        break;
      case 'created_table':
        $ruta_elegida = 'scripts/quote/created_table.php';
        break;
      case 'completed_table':
        $ruta_elegida = 'scripts/quote/completed_table.php';
        break;
      case 'submitted_table':
        $ruta_elegida = 'scripts/quote/submitted_table.php';
        break;
      case 'award_table':
        $ruta_elegida = 'scripts/quote/award_table.php';
        break;
      case 'no_bid_table':
        $ruta_elegida = 'scripts/quote/no_bid_table.php';
        break;
      case 'not_submitted_table':
        $ruta_elegida = 'scripts/quote/not_submitted_table.php';
        break;
      case 'cancelled_table':
        $ruta_elegida = 'scripts/quote/cancelled_table.php';
        break;
      case 'deleted_table':
        $ruta_elegida = 'scripts/quote/deleted_table.php';
        break;
      case 'reports':
        $ruta_elegida = 'scripts/quote/reports.php';
        break;
      case 'ids':
        $ruta_elegida = 'scripts/quote/ids.php';
        break;
      case 'link_quote':
        $ruta_elegida = 'scripts/quote/link_quote.php';
        break;
      case 'service':
        switch ($partes_ruta[3]) {
          case 'add_service':
            $ruta_elegida = 'scripts/service/add_service.php';
            break;
          case 'edit_service';
            $ruta_elegida = 'scripts/service/edit_service.php';
            break;
          case 'load_service';
            $id_service = $partes_ruta[4];
            $ruta_elegida = 'scripts/service/load_service.php';
            break;
          case 'delete_service';
            $id_service = $partes_ruta[4];
            $ruta_elegida = 'scripts/service/delete_service.php';
            break;
          case 'duplicate_service';
            $id_service = $partes_ruta[4];
            $ruta_elegida = 'scripts/service/duplicate_service.php';
            break;
          default:
            break;
        }
        break;
      case 'equipment':
        switch ($partes_ruta[3]) {
          case 'guardar_add_item':
            $id_rfq = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/guardar_add_item.php';
            break;
          case 'guardar_edit_item':
            $id_item = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/guardar_edit_item.php';
            break;
          case 'delete_item':
            $id_item = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/delete_item.php';
            break;
          case 'guardar_add_provider':
            $id_item = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/guardar_add_provider.php';
            break;
          case 'guardar_edit_provider':
            $id_provider = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/guardar_edit_provider.php';
            break;
          case 'delete_provider':
            $id_provider = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/delete_provider.php';
            break;
          case 'guardar_add_subitem':
            $id_item = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/guardar_add_subitem.php';
            break;
          case 'guardar_edit_subitem':
            $id_subitem = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/guardar_edit_subitem.php';
            break;
          case 'delete_subitem':
            $id_subitem = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/delete_subitem.php';
            break;
          case 'guardar_add_provider_subitem':
            $id_subitem = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/guardar_add_provider_subitem.php';
            break;
          case 'guardar_edit_provider_subitem':
            $id_provider_subitem = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/guardar_edit_provider_subitem.php';
            break;
          case 'delete_provider_subitem':
            $id_provider_subitem = $partes_ruta[4];
            $ruta_elegida = 'scripts/quote/delete_provider_subitem.php';
            break;
          default:
            break;
        }
      case 'rooms':
        switch ($partes_ruta[3]) {
          case 'load':
            $ruta_elegida = 'scripts/quote/rooms/load.php';
            break;
          case 'save':
            $ruta_elegida = 'scripts/quote/rooms/save.php';
            break;
          case 'update':
            $ruta_elegida = 'scripts/quote/rooms/update.php';
            break;
          case 'delete':
            $ruta_elegida = 'scripts/quote/rooms/delete.php';
            break;
          default:
            break;
        }
        break;
      case 'proposal':
        $id_rfq = $partes_ruta[3];
        $encabezado = 0;
        $ruta_elegida = 'scripts/utilities/proposal.php';
        break;
      case 'proposal_gsa':
        $id_rfq = $partes_ruta[3];
        $encabezado = 1;
        $ruta_elegida = 'scripts/utilities/proposal.php';
        break;
      case 'proposal_room':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/utilities/proposal_room.php';
        break;
      case 'generate_checklist_pdf':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/utilities/generate_checklist_pdf.php';
        break;
      case 'get_quote_files':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/utilities/get_quote_files.php';
        break;
      case 'copy_quote':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/quote/copy_quote.php';
        break;
      case 'delete_quote':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/quote/delete_quote.php';
        break;
      case 'restore_quote':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/quote/restore_quote.php';
        break;
      case 'guardar_editar_cotizacion':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/quote/guardar_editar_cotizacion.php';
        break;
      case 'pdf_tabla_items';
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/utilities/pdf_tabla_items.php';
        break;
      case 'excel_items_table';
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/utilities/excel_items_table.php';
        break;
      case 'load_img':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/utilities/load_img.php';
        break;
      case 'remove_award':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/quote/remove_award.php';
        break;
      case 'remove_fulfillment':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/quote/remove_fulfillment.php';
        break;
      case 'remove_invoice':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/quote/remove_invoice.php';
        break;
      case 'remove_submitted_invoice':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/quote/remove_submitted_invoice.php';
        break;
      case 'delete_document':
        $id_rfq = $partes_ruta[3];
        $archivo = $partes_ruta[4];
        $ruta_elegida = 'scripts/utilities/delete_document.php';
        break;
      case 'download_all':
        $ruta_elegida = 'scripts/utilities/download_all.php';
        break;
      default:
        break;
    }
    break;
  case 'user':
    switch ($partes_ruta[2]) {
      case 'users':
        $ruta_elegida = 'scripts/user/users.php';
        break;
      case 'recover_password_form':
        $ruta_elegida = 'herramientas/recover_password_form.php';
        break;
      case 'enable_user':
        $id_usuario = $partes_ruta[3];
        $ruta_elegida = 'scripts/user/enable_user.php';
        break;
      case 'disable_user':
        $id_usuario = $partes_ruta[3];
        $ruta_elegida = 'scripts/user/disable_user.php';
        break;
      case 'restart_password':
        $url_secreta = $partes_ruta[3];
        $ruta_elegida = 'herramientas/restart_password.php';
        break;
      case 'update':
        $ruta_elegida = 'scripts/user/update_user.php';
        break;
      case 'create':
        $ruta_elegida = 'scripts/user/create_user.php';
        break;
      case 'update_password':
        $ruta_elegida = 'scripts/user/update_password.php';
        break;
      default:
        break;
    }
    break;
  case 're_quote_sc':
    switch ($partes_ruta[2]) {
      case 'save_re_quote_item':
        $ruta_elegida = 'scripts/re_quote/save_re_quote_item.php';
        break;
      case 'save_edit_re_quote_item':
        $ruta_elegida = 'scripts/re_quote/save_edit_re_quote_item.php';
        break;
      case 'delete_re_quote_item':
        $id_re_quote_item = $partes_ruta[3];
        $ruta_elegida = 'scripts/re_quote/delete_re_quote_item.php';
        break;
      case 'save_re_quote_provider':
        $ruta_elegida = 'scripts/re_quote/save_re_quote_provider.php';
        break;
      case 'save_edit_re_quote_provider':
        $ruta_elegida = 'scripts/re_quote/save_edit_re_quote_provider.php';
        break;
      case 'delete_re_quote_provider':
        $id_re_quote_provider = $partes_ruta[3];
        $ruta_elegida = 'scripts/re_quote/delete_re_quote_provider.php';
        break;
      case 'save_re_quote_subitem':
        $ruta_elegida = 'scripts/re_quote/save_re_quote_subitem.php';
        break;
      case 'save_edit_re_quote_subitem':
        $ruta_elegida = 'scripts/re_quote/save_edit_re_quote_subitem.php';
        break;
      case 'delete_re_quote_subitem':
        $id_re_quote_subitem = $partes_ruta[3];
        $ruta_elegida = 'scripts/re_quote/delete_re_quote_subitem.php';
        break;
      case 'save_re_quote_subitem_provider':
        $ruta_elegida = 'scripts/re_quote/save_re_quote_subitem_provider.php';
        break;
      case 'save_edit_re_quote_subitem_provider':
        $ruta_elegida = 'scripts/re_quote/save_edit_re_quote_subitem_provider.php';
        break;
      case 'delete_re_quote_subitem_provider':
        $id_re_quote_subitem_provider = $partes_ruta[3];
        $ruta_elegida = 'scripts/re_quote/delete_re_quote_subitem_provider.php';
        break;
      case 'save_re_quote':
        $ruta_elegida = 'scripts/re_quote/save_re_quote.php';
        break;
      case 'pdf_re_quote';
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/utilities/pdf_re_quote.php';
        break;
      case 'reload_requote':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/re_quote/reload_requote.php';
        break;
      case 'load_service':
        $id_service = $partes_ruta[3];
        $ruta_elegida = 'scripts/re_quote/load_service.php';
        break;
      case 'update_service':
        $id_service = $partes_ruta[3] ?? null;
        $ruta_elegida = 'scripts/re_quote/update_service.php';
        break;
      default:
        break;
    }
    break;
  case 'tracking':
    switch ($partes_ruta[2]) {
      case 'save_tracking':
        $ruta_elegida = 'scripts/tracking/save_tracking.php';
        break;
      case 'save_edit_tracking':
        $ruta_elegida = 'scripts/tracking/save_edit_tracking.php';
        break;
      case 'delete_tracking':
        $id_tracking = $partes_ruta[3];
        $ruta_elegida = 'scripts/tracking/delete_tracking.php';
        break;
      case 'load_tracking':
        $id_tracking = $partes_ruta[3];
        $ruta_elegida = 'scripts/tracking/load_tracking.php';
        break;
      case 'save_tracking_subitem':
        $ruta_elegida = 'scripts/tracking/save_tracking_subitem.php';
        break;
      case 'save_edit_tracking_subitem':
        $ruta_elegida = 'scripts/tracking/save_edit_tracking_subitem.php';
        break;
      case 'delete_tracking_subitem':
        $id_tracking_subitem = $partes_ruta[3];
        $ruta_elegida = 'scripts/tracking/delete_tracking_subitem.php';
        break;
      case 'load_tracking_subitem':
        $id_tracking_subitem = $partes_ruta[3];
        $ruta_elegida = 'scripts/tracking/load_tracking_subitem.php';
        break;
      case 'load_tracking_box':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/tracking/load_tracking_box.php';
        break;
      case 'tracking_pdf':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/tracking/tracking_pdf.php';
        break;
      case 'tracking_excel':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/tracking/tracking_excel.php';
        break;
      default:
        break;
    }
    break;
  case 'fulfillment':
    switch ($partes_ruta[2]) {
      case 'equipment':
        switch ($partes_ruta[3]) {
          case 'save_fulfillment_item':
            $ruta_elegida = 'scripts/fulfillment/save_fulfillment_item.php';
            break;
          case 'save_edit_fulfillment_item':
            $ruta_elegida = 'scripts/fulfillment/save_edit_fulfillment_item.php';
            break;
          case 'delete_fulfillment_item':
            $ruta_elegida = 'scripts/fulfillment/delete_fulfillment_item.php';
            break;
          case 'load_fulfillment_item':
            $ruta_elegida = 'scripts/fulfillment/load_fulfillment_item.php';
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
          case 'load_fulfillment_subitem':
            $ruta_elegida = 'scripts/fulfillment/load_fulfillment_subitem.php';
            break;
          case 'mark_as_reviewed':
            $ruta_elegida = 'scripts/fulfillment/mark_as_reviewed.php';
            break;
          case 'mark_subitem_as_reviewed':
            $ruta_elegida = 'scripts/fulfillment/mark_subitem_as_reviewed.php';
            break;
          case 'update_fulfillment_shipping':
            $ruta_elegida = 'scripts/fulfillment/update_fulfillment_shipping.php';
            break;
          case 'load_fulfillment_shipping':
            $id_rfq = $partes_ruta[4];
            $ruta_elegida = 'scripts/fulfillment/load_fulfillment_shipping.php';
            break;
          case 'save_net_30':
            $ruta_elegida = 'scripts/fulfillment/save_net_30.php';
            break;
        }
        break;
      case 'service':
        switch ($partes_ruta[3]) {
          case 'save_fulfillment_service':
            $ruta_elegida = 'scripts/fulfillment/save_fulfillment_service.php';
            break;
          case 'save_edit_fulfillment_service':
            $ruta_elegida = 'scripts/fulfillment/save_edit_fulfillment_service.php';
            break;
          case 'delete_fulfillment_service':
            $ruta_elegida = 'scripts/fulfillment/delete_fulfillment_service.php';
            break;
          case 'load_fulfillment_service':
            $ruta_elegida = 'scripts/fulfillment/load_fulfillment_service.php';
            break;
          case 'save_net_30':
            $ruta_elegida = 'scripts/fulfillment/save_net_30_services.php';
            break;
          case 'mark_as_reviewed_service':
            $ruta_elegida = 'scripts/fulfillment/mark_as_reviewed_service.php';
            break;
          default:
            break;
        }
        break;
      case 'personnel':
        switch ($partes_ruta[3]) {
          case 'table':
            $ruta_elegida = 'scripts/fulfillment/personnel/table.php';
            break;
          case 'save':
            $ruta_elegida = 'scripts/fulfillment/personnel/save.php';
            break;
          case 'load':
            $ruta_elegida = 'scripts/fulfillment/personnel/load.php';
            break;
          case 'update':
            $ruta_elegida = 'scripts/fulfillment/personnel/update.php';
            break;
          case 'delete':
            $ruta_elegida = 'scripts/fulfillment/personnel/delete.php';
            break;
          case 'get_personnel_events':
            $ruta_elegida = 'scripts/fulfillment/personnel/get_personnel_events.php';
            break;
          default:
            break;
        }
        break;
      case 'personnel_calendar':
        switch ($partes_ruta[3]) {
          case 'save':
            $ruta_elegida = 'scripts/fulfillment/personnel_calendar/save.php';
            break;
          case 'load':
            $ruta_elegida = 'scripts/fulfillment/personnel_calendar/load.php';
            break;
          case 'update':
            $ruta_elegida = 'scripts/fulfillment/personnel_calendar/update.php';
            break;
          case 'delete':
            $ruta_elegida = 'scripts/fulfillment/personnel_calendar/delete.php';
            break;
          case 'save_shared_event':
            $ruta_elegida = 'scripts/fulfillment/personnel_calendar/save_shared_event.php';
            break;
          default:
            break;
        }
        break;
      case 'type_of_project':
        switch ($partes_ruta[3]) {
          case 'table':
            $ruta_elegida = 'scripts/fulfillment/type_of_project/table.php';
            break;
          case 'save':
            $ruta_elegida = 'scripts/fulfillment/type_of_project/save.php';
            break;
          case 'load':
            $ruta_elegida = 'scripts/fulfillment/type_of_project/load.php';
            break;
          case 'update':
            $ruta_elegida = 'scripts/fulfillment/type_of_project/update.php';
            break;
          case 'delete':
            $ruta_elegida = 'scripts/fulfillment/type_of_project/delete.php';
            break;
          default:
            break;
        }
        break;
      case 'invoice':
        switch ($partes_ruta[3]) {
          case 'save':
            $ruta_elegida = 'scripts/fulfillment/invoice/save.php';
            break;
          case 'load':
            $ruta_elegida = 'scripts/fulfillment/invoice/load.php';
            break;
          case 'load_dropdown':
            $ruta_elegida = 'scripts/fulfillment/invoice/load_dropdown.php';
            break;
          case 'update':
            $ruta_elegida = 'scripts/fulfillment/invoice/update.php';
            break;
          case 'delete':
            $ruta_elegida = 'scripts/fulfillment/invoice/delete.php';
            break;
          case 'attach_sales_commission':
            $ruta_elegida = 'scripts/fulfillment/invoice/attach_sales_commission.php';
            break;
          default:
            break;
        }
        break;
      case 'fulfillment_quotes_table':
        $ruta_elegida = 'scripts/fulfillment/fulfillment_quotes_table.php';
        break;
      case 'load_fulfillment_audit_trails':
        $ruta_elegida = 'scripts/fulfillment/load_fulfillment_audit_trails.php';
        break;
      case 'load_fulfillment_page':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/fulfillment/load_fulfillment_page.php';
        break;
      case 'mark_as_pending':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/fulfillment/mark_as_pending.php';
        break;
      case 'unmark_as_pending':
        $id_rfq = $partes_ruta[3];
        $ruta_elegida = 'scripts/fulfillment/unmark_as_pending.php';
        break;
      case 'delete_invoice':
        $id_invoice = $partes_ruta[3];
        $ruta_elegida = 'scripts/fulfillment/delete_invoice.php';
        break;
      case 'update_invoice':
        $ruta_elegida = 'scripts/fulfillment/update_invoice.php';
        break;
      default:
        break;
    }
    break;
  case 'projection':
    switch ($partes_ruta[2]) {
      case 'table':
        $ruta_elegida = 'scripts/projection/table.php';
        break;
      case 'save':
        $ruta_elegida = 'scripts/projection/save.php';
        break;
      case 'monthly':
        $ruta_elegida = 'scripts/projection/monthly.php';
        break;
      case 'month':
        $ruta_elegida = 'scripts/projection/month.php';
        break;
      case 'projected_amount':
        $ruta_elegida = 'scripts/projection/projected_amount.php';
        break;
      case 'update_projected_amount':
        $ruta_elegida = 'scripts/projection/update_projected_amount.php';
        break;
      case 'get_totals':
        $ruta_elegida = 'scripts/projection/get_totals.php';
        break;
      case 'get_month_totals':
        $ruta_elegida = 'scripts/projection/get_month_totals.php';
        break;
      case 'invoice_acceptance':
        $ruta_elegida = 'scripts/projection/invoice_acceptance.php';
        break;
      case 'update_invoice_acceptance':
        $ruta_elegida = 'scripts/projection/update_invoice_acceptance.php';
        break;
      case 'month_excel':
        $id = $partes_ruta[3];
        $ruta_elegida = 'scripts/projection/month_excel.php';
        break;
      case 'charts':
        $ruta_elegida = 'scripts/projection/charts.php';
        break;
      default:
        break;
    }
    break;
  case 'invoice':
    switch ($partes_ruta[2]) {
      case 'invoice_quotes_table':
        $ruta_elegida = 'scripts/invoice/invoice_quotes_table.php';
        break;
      default:
        break;
    }
    break;
  case 'submitted_invoice':
    switch ($partes_ruta[2]) {
      case 'submitted_invoice_quotes_table':
        $ruta_elegida = 'scripts/submitted_invoice/submitted_invoice_quotes_table.php';
        break;
      default:
        break;
    }
    break;
  case 'provider':
    switch ($partes_ruta[2]) {
      case 'save_provider':
        $ruta_elegida = 'scripts/providers/save_provider.php';
        break;
      case 'load_providers_table':
        $ruta_elegida = 'scripts/providers/load_providers_table.php';
        break;
      case 'update_provider':
        $ruta_elegida = 'scripts/providers/update_provider.php';
        break;
      case 'delete_provider':
        $ruta_elegida = 'scripts/providers/delete_provider.php';
        break;
      case 'load_provider':
        $id_provider = $partes_ruta[3];
        $ruta_elegida = 'scripts/providers/load_provider.php';
        break;
      default:
        break;
    }
    break;
  case 'payment_term':
    switch ($partes_ruta[2]) {
      case 'load_payment_terms_table':
        $ruta_elegida = 'scripts/payment_terms/load_payment_terms_table.php';
        break;
      case 'save_payment_term':
        $ruta_elegida = 'scripts/payment_terms/save_payment_term.php';
        break;
      case 'update_payment_term':
        $ruta_elegida = 'scripts/payment_terms/update_payment_term.php';
        break;
      case 'delete_payment_term':
        $ruta_elegida = 'scripts/payment_terms/delete_payment_term.php';
        break;
      case 'load_payment_term':
        $id_payment_term = $partes_ruta[3];
        $ruta_elegida = 'scripts/payment_terms/load_payment_term.php';
        break;
      default:
        break;
    }
    break;
  case 'task':
    switch ($partes_ruta[2]) {
      case 'save_task':
        $ruta_elegida = 'scripts/tasks/save_task.php';
        break;
      case 'load_tasks_board':
        $ruta_elegida = 'scripts/tasks/load_tasks_board.php';
        break;
      case 'update_task':
        $ruta_elegida = 'scripts/tasks/update_task.php';
        break;
      case 'load_my_tasks_board':
        $ruta_elegida = 'scripts/tasks/load_my_tasks_board.php';
        break;
      case 'load_tasks_done_table':
        $ruta_elegida = 'scripts/tasks/load_tasks_done_table.php';
        break;
      case 'load_task':
        $id_task = $partes_ruta[3];
        $ruta_elegida = 'scripts/tasks/load_task.php';
        break;
      default:
        break;
    }
    break;
  case 'kpi':
    $id_rfq = $partes_ruta[2];
    $ruta_elegida = 'scripts/kpi/load_kpi_charts.php';
    break;
  case 'utilities':
    switch ($partes_ruta[2]) {
      case 'search_quotes':
        $ruta_elegida = 'scripts/utilities/search_quotes.php';
        break;
      default:
        break;
    }
    break;
  default:
    break;
}
include_once $ruta_elegida;
