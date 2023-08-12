<?php
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote(Conexion::obtener_conexion(), $re_quote->get_id());
Conexion::cerrar_conexion();

$pdfGenerator = new PDFGenerator(true);

ob_start();

require_once 'herramientas/pdfTemplates/tracking.inc.php';

$html = ob_get_clean();

$pdfGenerator->setFooter('
  <div class="color letra_chiquita" style="text-align:center;">
    EIN: 51-0629765, DUNS: 786-965876, CAGE:4QTF4<br>SBA 8(a) and HUBZone certified
  </div>
');

$pdfGenerator->generatePDF($html);
$pdfGenerator->saveInServer($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion->obtener_id() . '/' . preg_replace('/[^a-z0-9-_\-\.]/i', '_', $cotizacion->obtener_email_code()) . '(trackings)' . '.pdf');
$pdfGenerator->display(preg_replace('/[^a-z0-9-_\-\.]/i', '_', $cotizacion->obtener_email_code()) . '(trackings).pdf');
