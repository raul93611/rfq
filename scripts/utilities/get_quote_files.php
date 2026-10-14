<?php
header('Content-Type: application/json');

$path = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
$archivos = [];

try {
  if (is_dir($path)) {
    if ($gestor = opendir($path)) {
      while (($archivo = readdir($gestor)) !== false) {
        if ($archivo !== "." && $archivo !== "..") {
          $archivos[] = $archivo;
        }
      }
      closedir($gestor);
    } else {
      throw new Exception("Failed to open directory: $path");
    }
  } else {
    throw new Exception("Directory does not exist: $path");
  }
} catch (Exception $e) {
  // Print the exception message
  print "Error: " . $e->getMessage();
  exit;
}

// Partner Opportunity Sync: per-file share state for the Documents drawer. Never fatal — the
// file list above is what the widget needs; the share marks are an enhancement.
$partner = array('sharing' => false, 'states' => new stdClass());
try {
  Conexion::abrir_conexion();
  $c = Conexion::obtener_conexion();
  $quote = PartnerShareRepository::row($c, (int)$id_rfq);
  if ($quote) {
    PartnerDocumentRepository::syncFolder($c, (int)$id_rfq);
    $partner = array(
      'sharing' => (int)$quote['share_with_partner'] === 1 && (int)$quote['deleted'] === 0,
      'states' => PartnerDocumentRepository::stateByName($c, (int)$id_rfq) ?: new stdClass(),
    );
  }
  Conexion::cerrar_conexion();
} catch (Throwable $e) {
  error_log('get_quote_files partner state: ' . $e->getMessage());
}

echo json_encode(array(
  'files' => $archivos,
  'partner' => $partner,
));
