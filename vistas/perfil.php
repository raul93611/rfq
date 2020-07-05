<?php
if(!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
$titulo = 'Profile';
include_once 'plantillas/documento_declaracion.inc.php';
include_once 'plantillas/navbar.inc.php';
include_once 'plantillas/barra_lateral.inc.php';
switch ($gestor_actual) {
  case '':
    include_once 'plantillas/muro.inc.php';
    break;
  case 're_quote':
    include_once 'plantillas/re_quote.inc.php';
    break;
  case 'add_re_quote_item':
    include_once 'plantillas/add_re_quote_item.inc.php';
    break;
  case 'edit_re_quote_item':
    include_once 'plantillas/edit_re_quote_item.inc.php';
    break;
  case 'add_re_quote_provider':
    include_once 'plantillas/add_re_quote_provider.inc.php';
    break;
  case 'edit_re_quote_provider':
    include_once 'plantillas/edit_re_quote_provider.inc.php';
    break;
  case 'add_re_quote_subitem':
    include_once 'plantillas/add_re_quote_subitem.inc.php';
    break;
  case 'edit_re_quote_subitem':
    include_once 'plantillas/edit_re_quote_subitem.inc.php';
    break;
  case 'add_re_quote_subitem_provider':
    include_once 'plantillas/add_re_quote_subitem_provider.inc.php';
    break;
  case 'edit_re_quote_subitem_provider':
    include_once 'plantillas/edit_re_quote_subitem_provider.inc.php';
    break;
  case 'registro':
    if ($_SESSION['cargo'] == 1) {
      include_once 'plantillas/registro.inc.php';
    } else {
      include_once 'plantillas/muro.inc.php';
    }
    break;
  case 'search_quotes':
    include_once 'plantillas/search_quotes.inc.php';
    break;
  case 'employee_docs_page':
    include_once 'plantillas/employee_docs_page.inc.php';
    break;
  case 'edit_user':
    include_once 'plantillas/edit_user.inc.php';
    break;
  case 'cotizaciones':
    switch ($cotizacion) {
      case 'nuevo':
        include_once 'plantillas/nueva_cotizacion.inc.php';
        break;
      case 'editar_cotizacion':
        Conexion::abrir_conexion();
        $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
        Conexion::cerrar_conexion();
        include_once 'plantillas/editar_cotizacion.inc.php';
        break;
      case 'delete_quote':
        Conexion::abrir_conexion();
        $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
        Conexion::cerrar_conexion();
        include_once 'scripts/delete_quote.php';
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
      case 'add_provider_subitem':
        Conexion::abrir_conexion();
        $subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $id_subitem);
        Conexion::cerrar_conexion();
        include_once 'plantillas/add_provider_subitem.inc.php';
        break;
      case 'add_subitem':
        Conexion::abrir_conexion();
        $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
        Conexion::cerrar_conexion();
        include_once 'plantillas/add_subitem.inc.php';
        break;
      case 'edit_item':
        Conexion::abrir_conexion();
        $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
        Conexion::cerrar_conexion();
        include_once 'plantillas/edit_item.inc.php';
        break;
      case 'edit_subitem':
        Conexion::abrir_conexion();
        $subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $id_subitem);
        Conexion::cerrar_conexion();
        include_once 'plantillas/edit_subitem.inc.php';
        break;
      case 'edit_provider':
        Conexion::abrir_conexion();
        $provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $id_provider);
        $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider->obtener_id_item());
        Conexion::cerrar_conexion();
        include_once 'plantillas/edit_provider.inc.php';
        break;
      case 'edit_provider_subitem':
        Conexion::abrir_conexion();
        $provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $id_provider_subitem);
        $subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem-> obtener_id_subitem());
        Conexion::cerrar_conexion();
        include_once 'plantillas/edit_provider_subitem.inc.php';
        break;
      default :
        include_once 'plantillas/cotizaciones.inc.php';
        break;
      }
      break;
  case 'completados':
    include_once 'plantillas/completados.inc.php';
    break;
  case 'submitted':
    include_once 'plantillas/submitted.inc.php';
    break;
  case 'award':
    include_once 'plantillas/award.inc.php';
    break;
  case 'excel_reports':
    include_once 'plantillas/excel_reports.inc.php';
    break;
  case 'no_bid':
    include_once 'plantillas/no_bid.inc.php';
    break;
  case 'fulfillment_quotes':
    include_once 'plantillas/fulfillment_quotes.inc.php';
    break;
  case 'no_submitted':
    include_once 'plantillas/no_submitted.inc.php';
    break;
  case 'rfp_quotes':
    include_once 'plantillas/rfp_quotes.inc.php';
    break;
  case 'cancelled':
    include_once 'plantillas/cancelled.inc.php';
    break;
}
include_once 'plantillas/documento_cierre.inc.php';
?>
