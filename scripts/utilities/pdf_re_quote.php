<?php
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote(Conexion::obtener_conexion(), $re_quote->get_id());
$re_quote_services = ReQuoteServiceRepository::get_services(Conexion::obtener_conexion(), $re_quote->get_id());
$total_services = ReQuoteServiceRepository::get_total(Conexion::obtener_conexion(), $re_quote->get_id());
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$services = ServiceRepository::get_services(Conexion::obtener_conexion(), $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
Conexion::cerrar_conexion();
$partes_fecha_completado = explode('-', $cotizacion->obtener_fecha_completado());
$fecha_completado = $partes_fecha_completado[1] . '/' . $partes_fecha_completado[2] . '/' . $partes_fecha_completado[0];
$partes_expiration_date = explode('-', $cotizacion->obtener_expiration_date());
$expiration_date = $partes_expiration_date[1] . '/' . $partes_expiration_date[2] . '/' . $partes_expiration_date[0];

$pdfGenerator = new PDFGenerator(true);

ob_start();

require_once 'herramientas/pdfTemplates/re_quote.inc.php';

$html = ob_get_clean();

$pdfGenerator->setFooter('
  <div class="color letra_chiquita" style="text-align:center;">
    EIN: 51-0629765, DUNS: 786-965876, CAGE:4QTF4<br>SBA 8(a) and HUBZone certified
  </div>
');

$pdfGenerator->generatePDF($html);
$pdfGenerator->saveInServer($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion->obtener_id() . '/' . preg_replace('/[^a-z0-9-_\-\.]/i', '_', $cotizacion->obtener_email_code()) . '(re_quote_items_table)' . '.pdf');
$pdfGenerator->display(preg_replace('/[^a-z0-9-_\-\.]/i', '_', $cotizacion->obtener_email_code()) . '(re_quote_items_table).pdf');
