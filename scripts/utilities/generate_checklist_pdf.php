<?php
$checkbox = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20"><rect width="20" height="20" fill="none" stroke="#000000" stroke-width="2"/></svg>';
$check = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20"><path fill="#00b300" d="M7.87 15.116l-4.914-4.914 1.767-1.767 3.147 3.146 7.779-7.779 1.767 1.768z"/></svg>';
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $quote->obtener_usuario_designado());
Conexion::cerrar_conexion();
$logo = 'logo_proposal.jpg';

$pdfGenerator = new PDFGenerator();

ob_start();

require_once 'herramientas/pdfTemplates/checklist.inc.php';

$html = ob_get_clean();

$pdfGenerator->generatePDF($html);
$pdfGenerator->saveInServer($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $quote->obtener_id() . '/' . preg_replace('/[^a-z0-9-_\-\.]/i', '_', $quote->obtener_email_code()) . '(checklist)' . '.pdf');
$pdfGenerator->display(preg_replace('/[^a-z0-9-_\-\.]/i', '_', $quote->obtener_email_code()) . '(checklist).pdf');
