<?php
// Open the database connection
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

try {
  // Fetch necessary data
  $cotizacion = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
  $usuario_designado = RepositorioUsuario::obtener_usuario_por_id($conexion, $cotizacion->obtener_usuario_designado());
  $items = RepositorioItem::obtener_items_por_id_rfq($conexion, $id_rfq);
} finally {
  // Ensure the database connection is closed
  Conexion::cerrar_conexion();
}

// Format dates
$fecha_completado = date("m/d/Y", strtotime($cotizacion->obtener_fecha_completado()));
$expiration_date = date("m/d/Y", strtotime($cotizacion->obtener_expiration_date()));

// Initialize PDF generator
$pdfGenerator = new PDFGenerator(true);

try {
  // Start output buffering
  ob_start();

  // Generate HTML content for the PDF
  include 'herramientas/pdfTemplates/items_table.inc.php';
  $html = ob_get_clean();

  // Set PDF footer
  $pdfGenerator->setFooter('
        <div class="color letra_chiquita" style="text-align:center;">
        EIN: 51-0629765, DUNS: 786-965876, CAGE: 4QTF4<br>SBA 8(a) and HUBZone certified
        </div>
    ');

  // Generate and display the PDF
  $pdfGenerator->generatePDF($html);
  $pdfFileName = sprintf('%s(items_table).pdf', Input::sanitize_filename($cotizacion->obtener_email_code()));
  $pdfGenerator->display($pdfFileName);
} catch (Exception $e) {
  // Handle PDF generation errors
  echo 'Error generating PDF: ' . $e->getMessage();
}
