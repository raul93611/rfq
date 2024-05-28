<?php
try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch necessary data
  $cotizacion = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
  $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($conexion, $id_rfq);
  $re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote($conexion, $re_quote->get_id());
  $re_quote_services = ReQuoteServiceRepository::get_services($conexion, $re_quote->get_id());
  $total_services = ReQuoteServiceRepository::get_total($conexion, $re_quote->get_id());
  $items = RepositorioItem::obtener_items_por_id_rfq($conexion, $id_rfq);
  $services = ServiceRepository::get_services($conexion, $id_rfq);
  $usuario_designado = RepositorioUsuario::obtener_usuario_por_id($conexion, $cotizacion->obtener_usuario_designado());
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit;
} finally {
  Conexion::cerrar_conexion();
}

// Format dates
$fecha_completado = date("m/d/Y", strtotime($cotizacion->obtener_fecha_completado()));
$expiration_date = date("m/d/Y", strtotime($cotizacion->obtener_expiration_date()));

// Initialize PDF generator
$pdfGenerator = new PDFGenerator(true);

try {
  ob_start();

  // Generate HTML content for the PDF
  include 'herramientas/pdfTemplates/re_quote.inc.php';
  $html = ob_get_clean();

  // Set PDF footer
  $pdfGenerator->setFooter('
        <div class="color letra_chiquita" style="text-align:center;">
            EIN: 51-0629765, DUNS: 786-965876, CAGE: 4QTF4<br>SBA 8(a) and HUBZone certified
        </div>
    ');

  // Generate and save the PDF
  $pdfGenerator->generatePDF($html);
  $pdfPath = sprintf('%s/rfq/documentos/%s/%s(re_quote_items_table).pdf', $_SERVER['DOCUMENT_ROOT'], $cotizacion->obtener_id(), Input::sanitize_filename($cotizacion->obtener_email_code()));
  $pdfGenerator->saveInServer($pdfPath);

  // Display the PDF
  $pdfFileName = sprintf('%s(re_quote_items_table).pdf', Input::sanitize_filename($cotizacion->obtener_email_code()));
  $pdfGenerator->display($pdfFileName);
} catch (Exception $e) {
  echo 'Error generating PDF: ' . $e->getMessage();
}
