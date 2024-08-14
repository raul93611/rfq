<?php
header('Content-Type: application/json');

try {
  // Ensure the directory path is correct
  $directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;

  // Save the uploaded files
  Input::save_files($directorio, $_FILES['archivos_ejemplo'], $id_rfq);

  // Return success result
  echo json_encode(array('result' => '1'));
} catch (Exception $e) {
  // Return error result with the error message
  echo json_encode(array(
    'result' => '0',
    'error' => $e->getMessage()
  ));
}
