<?php
header('Content-Type: application/json');
$path = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
if (is_dir($path)) {
  $gestor = opendir($path);
  $carpeta = @scandir($path);
  if (count($carpeta) <= 2) {
  }
  $archivos = [];
  while (($archivo = readdir($gestor)) !== false) {
    $ruta_completa = $path . "/" . $archivo;
    if ($archivo != "." && $archivo != "..") {
      $archivos[] = $archivo;
    }
  }
  closedir($gestor);
}
echo json_encode(array(
  'files' => $archivos,
));
