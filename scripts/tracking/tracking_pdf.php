<?php
function fetchQuoteData($id_rfq) {
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
    $items = RepositorioItem::obtener_items_por_id_rfq($conexion, $id_rfq);
    $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($conexion, $id_rfq);
    $re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote($conexion, $re_quote->get_id());

    return [
      'cotizacion' => $cotizacion,
      'items' => $items,
      're_quote' => $re_quote,
      're_quote_items' => $re_quote_items,
    ];
  } catch (Exception $e) {
    echo 'Error fetching quote data: ' . $e->getMessage();
    return null;
  } finally {
    Conexion::cerrar_conexion();
  }
}

function generateAndSavePDF($cotizacion, $html) {
  try {
    $pdfGenerator = new PDFGenerator(true);

    $pdfGenerator->setFooter('
            <div class="color letra_chiquita" style="text-align:center;">
                EIN: 51-0629765, DUNS: 786-965876, CAGE:4QTF4<br>SBA 8(a) and HUBZone certified
            </div>
        ');

    $pdfGenerator->generatePDF($html);
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion->obtener_id() . '/' . Input::sanitize_filename($cotizacion->obtener_email_code()) . '(trackings).pdf';
    $pdfGenerator->saveInServer($filePath);
    $pdfGenerator->display($filePath);
  } catch (Exception $e) {
    echo 'Error generating or saving PDF: ' . $e->getMessage();
  }
}

// Assuming $id_rfq is already declared and coming from another file
if (isset($id_rfq)) {

  $quoteData = fetchQuoteData($id_rfq);

  if ($quoteData) {
    $cotizacion = $quoteData['cotizacion'];
    $items = $quoteData['items'];
    $re_quote = $quoteData['re_quote'];
    $re_quote_items = $quoteData['re_quote_items'];

    ob_start();
    include 'herramientas/pdfTemplates/tracking.inc.php';
    $html = ob_get_clean();

    generateAndSavePDF($cotizacion, $html);
  }
} else {
  echo 'Error: No RFQ ID provided.';
}
