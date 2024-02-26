<?php
include_once 'vendor/autoload.php';
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
if ($cotizacion->isServices()) {
  $services = ServiceRepository::get_services(Conexion::obtener_conexion(), $id_rfq);
  $total_service = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
  $payment_terms = $cotizacion->obtener_services_payment_term() ?? $cotizacion->obtener_payment_terms();
} else {
  $total_service = 0;
  $payment_terms = $cotizacion->obtener_payment_terms();
}
Conexion::cerrar_conexion();
$fecha_completado = RepositorioComment::mysql_date_to_english_format($cotizacion->obtener_fecha_completado());
$expiration_date = RepositorioComment::mysql_date_to_english_format($cotizacion->obtener_expiration_date());
$logo = $cotizacion->obtener_canal() == 'Stars III' ? 'logoSinergy.png' : 'extra_logo.jpg';

$pdfGenerator = new PDFGenerator();

ob_start();

require_once 'herramientas/pdfTemplates/proposal.inc.php';

$html = ob_get_clean();

// $pdfGenerator->setFooter('
//   <div class="color letra_chiquita" style="text-align:center;">
//   EIN: 51-0629765, DUNS: 786-965876, CAGE:4QTF4<br>SBA 8(a) and HUBZone certified
//   </div>
// ');

$pdfGenerator->generatePDF($html);
$pdfGenerator->saveInServer($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion->obtener_id() . '/' . preg_replace('/[^a-z0-9-_\-\.]/i', '_', $cotizacion->obtener_email_code()) . '(proposal)' . '.pdf');
$pdfGenerator->display(preg_replace('/[^a-z0-9-_\-\.]/i', '_', $cotizacion->obtener_email_code()) . '.pdf');
