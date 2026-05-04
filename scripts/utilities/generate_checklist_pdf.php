<?php
// Define SVG icons
$icons = [
  'checkbox' => '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20"><rect width="20" height="20" fill="none" stroke="#000000" stroke-width="2"/></svg>',
  'check' => '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20"><path fill="#00b300" d="M7.87 15.116l-4.914-4.914 1.767-1.767 3.147 3.146 7.779-7.779 1.767 1.768z"/></svg>',
  'cross' => '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20"><path fill="#ff0000" d="M14.43 14.43l-1.415 1.415L10 11.414 6.585 14.83l-1.415-1.415L8.585 10 5.17 6.585l1.415-1.415L10 8.586l3.415-3.415 1.415 1.415L11.414 10l3.415 3.415z"/></svg>'
];

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch quote and designated user
  $quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
  $usuario_designado = RepositorioUsuario::obtener_usuario_por_id($conexion, $quote->obtener_usuario_designado());
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
} finally {
  Conexion::cerrar_conexion();
}

// Define logo path
$logo = 'logo_proposal.png';

// Initialize PDF generator
$pdfGenerator = new PDFGenerator();

try {
  ob_start();

  // Generate HTML content for the PDF
  include 'herramientas/pdfTemplates/checklist.inc.php';
  $html = ob_get_clean();

  // Generate and save the PDF
  $pdfGenerator->generatePDF($html);
  $pdfPath = sprintf('%s/rfq/documentos/%s/%s(checklist).pdf', $_SERVER['DOCUMENT_ROOT'], $quote->obtener_id(), Input::sanitize_filename($quote->obtener_email_code()));
  $pdfGenerator->saveInServer($pdfPath);

  // Display the PDF
  $pdfFileName = sprintf('%s(checklist).pdf', Input::sanitize_filename($quote->obtener_email_code()));
  $pdfGenerator->display($pdfFileName);
} catch (Exception $e) {
  echo 'Error generating PDF: ' . $e->getMessage();
}
