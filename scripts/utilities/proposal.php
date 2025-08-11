<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

try {
    // Fetch necessary data
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
    $usuario_designado = RepositorioUsuario::obtener_usuario_por_id($conexion, $cotizacion->obtener_usuario_designado());
    $items = RepositorioItem::obtener_items_por_id_rfq($conexion, $id_rfq);

    // Conditional data fetching
    if ($cotizacion->isServices()) {
        $services = ServiceRepository::get_services($conexion, $id_rfq);
        $total_service = ServiceRepository::get_total($conexion, $id_rfq);
        $payment_terms = $cotizacion->obtener_services_payment_term() ?? $cotizacion->obtener_payment_terms();
    } else {
        $total_service = 0;
        $payment_terms = $cotizacion->obtener_payment_terms();
    }
} finally {
    // Ensure the database connection is closed
    Conexion::cerrar_conexion();
}

// Format dates
$fecha_completado = date("m/d/Y", strtotime($cotizacion->obtener_fecha_completado() ?? ''));
$expiration_date = date("m/d/Y", strtotime($cotizacion->obtener_expiration_date() ?? ''));

// Determine logo based on channel
$logo = ($cotizacion->obtener_canal() == 'Stars III') ? 'logoSinergy.png' : 'logo_proposal.jpg';

// Initialize PDF generator
$pdfGenerator = new PDFGenerator();

try {
    // Start output buffering
    ob_start();
    
    // Include template for generating HTML content
    require_once 'herramientas/pdfTemplates/proposal.inc.php';
    $html = ob_get_clean();
    
    // Set PDF footer
    $pdfGenerator->setFooter('
        <div class="color letra_chiquita" style="text-align:center;">
        FEIN: 51-0629765, DUNS: 786-965876, CAGE: 4QTF4, SAM UEI: G8SNQ1CZGNX5<br>SBA 8(a) and HUBZone certified
        </div>
    ');

    // Generate and save/display the PDF
    $sanitizedEmailCode = Input::sanitize_filename($cotizacion->obtener_email_code());
    $pdfFileName = sprintf('%s(proposal).pdf', $sanitizedEmailCode);
    $pdfGenerator->generatePDF($html);
    $pdfGenerator->saveInServer($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion->obtener_id() . '/' . $pdfFileName);
    $pdfGenerator->display($pdfFileName);

} catch (Exception $e) {
    // Handle PDF generation errors
    echo 'Error generating PDF: ' . $e->getMessage();
}
