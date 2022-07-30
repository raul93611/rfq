<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
$titulo = 'Profile';
include_once 'plantillas/utilities/documento_declaracion.inc.php';
include_once 'plantillas/utilities/navbar.inc.php';
include_once 'plantillas/utilities/barra_lateral.inc.php';
switch ($partes_ruta[2]) {
  case '':
    include_once 'plantillas/utilities/muro.inc.php';
    break;
  case 'user':
    switch ($partes_ruta[3]) {
      case 'registro':
        if ($_SESSION['user']->is_admin()) {
          include_once 'plantillas/user/registro.inc.php';
        } else {
          include_once 'plantillas/utilities/muro.inc.php';
        }
        break;
      case 'users':
        include_once 'plantillas/utilities/muro_admin.inc.php';
        break;
      case 'edit_user':
        $id_user = $partes_ruta[4];
        include_once 'plantillas/user/edit_user.inc.php';
        break;
      default:
        break;
    }
    break;
  case 'quote':
    switch ($partes_ruta[3]) {
      case 'no_bid':
        include_once 'plantillas/no_bid/no_bid.inc.php';
        break;
      case 'no_submitted':
        include_once 'plantillas/no_submitted/no_submitted.inc.php';
        break;
      case 'cancelled':
        include_once 'plantillas/cancelled/cancelled.inc.php';
        break;
      case 'nuevo':
        include_once 'plantillas/quote/nueva_cotizacion.inc.php';
        break;
      case 'editar_cotizacion':
        $id_rfq = $partes_ruta[4];
        include_once 'plantillas/quote/editar_cotizacion.inc.php';
        break;
      case 'equipment':
        switch ($partes_ruta[4]) {
          case 'add_item':
            $id_rfq = $partes_ruta[5];
            include_once 'plantillas/quote/add_item.inc.php';
            break;
          case 'edit_item':
            $id_item = $partes_ruta[5];
            include_once 'plantillas/quote/edit_item.inc.php';
            break;
          case 'add_provider':
            $id_item = $partes_ruta[5];
            include_once 'plantillas/quote/add_provider.inc.php';
            break;
          case 'edit_provider':
            $id_provider = $partes_ruta[5];
            include_once 'plantillas/quote/edit_provider.inc.php';
            break;
          case 'add_subitem':
            $id_item = $partes_ruta[5];
            include_once 'plantillas/quote/add_subitem.inc.php';
            break;
          case 'edit_subitem':
            $id_subitem = $partes_ruta[5];
            include_once 'plantillas/quote/edit_subitem.inc.php';
            break;
          case 'add_provider_subitem':
            $id_subitem = $partes_ruta[5];
            include_once 'plantillas/quote/add_provider_subitem.inc.php';
            break;
          case 'edit_provider_subitem':
            $id_provider_subitem = $partes_ruta[5];
            include_once 'plantillas/quote/edit_provider_subitem.inc.php';
            break;
          default:
            break;
        }
        break;
      case 'channel':
        $cotizacion = urldecode($partes_ruta[4]);
        include_once 'plantillas/created/cotizaciones.inc.php';
        break;
      case 'completed':
        $cotizacion = urldecode($partes_ruta[4]);
        include_once 'plantillas/completed/completados.inc.php';
        break;
      case 'submitted':
        $cotizacion = urldecode($partes_ruta[4]);
        include_once 'plantillas/submitted/submitted.inc.php';
        break;
      case 'award':
        $cotizacion = urldecode($partes_ruta[4]);
        include_once 'plantillas/awards/award.inc.php';
        break;
      default:
        break;
    }
    break;
  case 're_quote':
    switch ($partes_ruta[3]) {
      case 'add_re_quote_item':
        $id_re_quote = $partes_ruta[4];
        include_once 'plantillas/re_quote/add_re_quote_item.inc.php';
        break;
      case 'edit_re_quote_item':
        $id_re_quote_item = $partes_ruta[4];
        include_once 'plantillas/re_quote/edit_re_quote_item.inc.php';
        break;
      case 'add_re_quote_provider':
        $id_re_quote_item = $partes_ruta[4];
        include_once 'plantillas/re_quote/add_re_quote_provider.inc.php';
        break;
      case 'edit_re_quote_provider':
        $id_re_quote_provider = $partes_ruta[4];
        include_once 'plantillas/re_quote/edit_re_quote_provider.inc.php';
        break;
      case 'add_re_quote_subitem':
        $id_re_quote_item = $partes_ruta[4];
        include_once 'plantillas/re_quote/add_re_quote_subitem.inc.php';
        break;
      case 'edit_re_quote_subitem':
        $id_re_quote_subitem = $partes_ruta[4];
        include_once 'plantillas/re_quote/edit_re_quote_subitem.inc.php';
        break;
      case 'add_re_quote_subitem_provider':
        $id_re_quote_subitem = $partes_ruta[4];
        include_once 'plantillas/re_quote/add_re_quote_subitem_provider.inc.php';
        break;
      case 'edit_re_quote_subitem_provider':
        $id_re_quote_subitem_provider = $partes_ruta[4];
        include_once 'plantillas/re_quote/edit_re_quote_subitem_provider.inc.php';
        break;
      default:
        $id_rfq = $partes_ruta[3];
        include_once 'plantillas/re_quote/re_quote.inc.php';
        break;
    }
    break;
  case 'employee_docs_page':
    include_once 'plantillas/utilities/employee_docs_page.inc.php';
    break;
  case 'reports':
    if (isset($_POST['generate_excel_report'])) {
      $ruta_elegida = 'scripts/utilities/excel_report_' . $_POST['report'] . '.php';
    } else {
      include_once 'plantillas/utilities/reports.inc.php';
    }
    break;
  case 'fulfillment':
    switch ($partes_ruta[3]) {
      case 'fulfillment_quotes':
        include_once 'plantillas/fulfillment/fulfillment_quotes.inc.php';
        break;
      case 'providers':
        include_once 'plantillas/providers/providers.inc.php';
        break;
      case 'payment_terms':
        include_once 'plantillas/payment_terms/payment_terms.inc.php';
        break;
      case 'weekly_projections':
        include_once 'plantillas/weekly_projections/weekly_projections.inc.php';
        break;
      default:
        $id_rfq = $partes_ruta[3];
        include_once 'plantillas/fulfillment/fulfillment.inc.php';
        break;
    }
    break;
  case 'accounting':
    switch ($partes_ruta[3]) {
      case 'invoice_quotes':
        include_once 'plantillas/invoice/invoice_quotes.inc.php';
        break;
      case 'submitted_invoice_quotes':
        include_once 'plantillas/submitted_invoice/submitted_invoice_quotes.inc.php';
        break;
      default:
        break;
    }
    break;
  case 'tasks':
    switch ($partes_ruta[3]) {
      case 'my_tasks':
        include_once 'plantillas/tasks/my_tasks.inc.php';
        break;
      case 'tasks_done':
        include_once 'plantillas/tasks/tasks_done.inc.php';
        break;
      case 'all_tasks':
        include_once 'plantillas/tasks/tasks.inc.php';
        break;
      default:
        break;
    }
    break;
  case 'charts':
    include_once 'plantillas/utilities/charts.inc.php';
    break;
  case 'search_quotes':
    include_once 'plantillas/utilities/search_quotes.inc.php';
    break;
  case 'tracking':
    $id_rfq = $partes_ruta[3];
    include_once 'plantillas/tracking/tracking.inc.php';
    break;
  case 'kpi':
    $id_rfq = $partes_ruta[3];
    include_once 'plantillas/kpi/kpi.inc.php';
    break;
  default:
    break;
}
include_once 'plantillas/utilities/documento_cierre.inc.php';
