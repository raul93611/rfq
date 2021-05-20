<?php
if(!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVER);
}
$titulo = 'Profile';
include_once 'plantillas/utilities/documento_declaracion.inc.php';
include_once 'plantillas/utilities/navbar.inc.php';
include_once 'plantillas/utilities/barra_lateral.inc.php';
switch ($gestor_actual) {
  case '':
    include_once 'plantillas/utilities/muro.inc.php';
    break;
  case 're_quote':
    include_once 'plantillas/re_quote/re_quote.inc.php';
    break;
  case 'add_re_quote_item':
    include_once 'plantillas/re_quote/add_re_quote_item.inc.php';
    break;
  case 'edit_re_quote_item':
    include_once 'plantillas/re_quote/edit_re_quote_item.inc.php';
    break;
  case 'add_re_quote_provider':
    include_once 'plantillas/re_quote/add_re_quote_provider.inc.php';
    break;
  case 'edit_re_quote_provider':
    include_once 'plantillas/re_quote/edit_re_quote_provider.inc.php';
    break;
  case 'add_re_quote_subitem':
    include_once 'plantillas/re_quote/add_re_quote_subitem.inc.php';
    break;
  case 'edit_re_quote_subitem':
    include_once 'plantillas/re_quote/edit_re_quote_subitem.inc.php';
    break;
  case 'add_re_quote_subitem_provider':
    include_once 'plantillas/re_quote/add_re_quote_subitem_provider.inc.php';
    break;
  case 'edit_re_quote_subitem_provider':
    include_once 'plantillas/re_quote/edit_re_quote_subitem_provider.inc.php';
    break;
  case 'register_user':
    if ($_SESSION['cargo'] == 1) {
      include_once 'plantillas/user/register_user.inc.php';
    } else {
      include_once 'plantillas/utilities/muro.inc.php';
    }
    break;
  case 'search_quotes':
    include_once 'plantillas/utilities/earch_quotes.inc.php';
    break;
  case 'employee_docs_page':
    include_once 'plantillas/utilities/employee_docs_page.inc.php';
    break;
  case 'edit_user':
    include_once 'plantillas/user/edit_user.inc.php';
    break;
  case 'quotes':
    switch ($cotizacion) {
      case 'new':
        include_once 'plantillas/quote/nueva_cotizacion.inc.php';
        break;
      case 'edit_quote':
        Database::open_connection();
        $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Database::get_connection(), $id_rfq);
        Database::close_connection();
        include_once 'plantillas/quote/edit_quote.inc.php';
        break;
      case 'add_item':
        Database::open_connection();
        $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Database::get_connection(), $id_rfq);
        Database::close_connection();
        include_once 'plantillas/quote/add_item.inc.php';
        break;
      case 'add_provider':
        Database::open_connection();
        $item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $id_item);
        Database::close_connection();
        include_once 'plantillas/quote/add_provider.inc.php';
        break;
      case 'add_provider_subitem':
        Database::open_connection();
        $subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $id_subitem);
        Database::close_connection();
        include_once 'plantillas/quote/add_provider_subitem.inc.php';
        break;
      case 'add_subitem':
        Database::open_connection();
        $item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $id_item);
        Database::close_connection();
        include_once 'plantillas/quote/add_subitem.inc.php';
        break;
      case 'edit_item':
        Database::open_connection();
        $item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $id_item);
        Database::close_connection();
        include_once 'plantillas/quote/edit_item.inc.php';
        break;
      case 'edit_subitem':
        Database::open_connection();
        $subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $id_subitem);
        Database::close_connection();
        include_once 'plantillas/quote/edit_subitem.inc.php';
        break;
      case 'edit_provider':
        Database::open_connection();
        $provider = RepositorioProvider::obtener_provider_por_id(Database::get_connection(), $id_provider);
        $item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $provider->obtener_id_item());
        Database::close_connection();
        include_once 'plantillas/quote/edit_provider.inc.php';
        break;
      case 'edit_provider_subitem':
        Database::open_connection();
        $provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Database::get_connection(), $id_provider_subitem);
        $subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $provider_subitem-> obtener_id_subitem());
        Database::close_connection();
        include_once 'plantillas/quote/edit_provider_subitem.inc.php';
        break;
      default :
        include_once 'plantillas/created/quotes.inc.php';
        break;
      }
      break;
  case 'complete':
    include_once 'plantillas/completed/complete.inc.php';
    break;
  case 'submitted':
    include_once 'plantillas/submitted/submitted.inc.php';
    break;
  case 'award':
    include_once 'plantillas/awards/award.inc.php';
    break;
  case 'excel_reports':
    include_once 'plantillas/utilities/excel_reports.inc.php';
    break;
  case 'no_bid':
    include_once 'plantillas/no_bid/no_bid.inc.php';
    break;
  case 'fulfillment_quotes':
    include_once 'plantillas/fulfillment/fulfillment_quotes.inc.php';
    break;
  case 'no_submitted':
    include_once 'plantillas/no_submitted/no_submitted.inc.php';
    break;
  case 'cancelled':
    include_once 'plantillas/cancelled/cancelled.inc.php';
    break;
  case 'tracking':
    include_once 'plantillas/tracking/tracking.inc.php';
    break;
  case 'fulfillment':
    include_once 'plantillas/fulfillment/fulfillment.inc.php';
    break;
}
include_once 'plantillas/utilities/documento_cierre.inc.php';
?>
