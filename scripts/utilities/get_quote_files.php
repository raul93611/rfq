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

echo json_encode(array(
  'files' => $archivos,
));
