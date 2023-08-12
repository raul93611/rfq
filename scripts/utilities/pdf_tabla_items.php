<?php
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
$partes_fecha_completado = explode('-', $cotizacion->obtener_fecha_completado());
$fecha_completado = $partes_fecha_completado[1] . '/' . $partes_fecha_completado[2] . '/' . $partes_fecha_completado[0];
$partes_expiration_date = explode('-', $cotizacion->obtener_expiration_date());
$expiration_date = $partes_expiration_date[1] . '/' . $partes_expiration_date[2] . '/' . $partes_expiration_date[0];

$pdfGenerator = new PDFGenerator(true);

ob_start();

require_once 'herramientas/pdfTemplates/items_table.inc.php';

$html = ob_get_clean();

$pdfGenerator->setFooter('
  <div class="color letra_chiquita" style="text-align:center;">
  EIN: 51-0629765, DUNS: 786-965876, CAGE:4QTF4<br>SBA 8(a) and HUBZone certified
  </div>
');

$pdfGenerator->generatePDF($html);
$pdfGenerator->display(preg_replace('/[^a-z0-9-_\-\.]/i', '_', $cotizacion->obtener_email_code()) . '(items_table).pdf');
