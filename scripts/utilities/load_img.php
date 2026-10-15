<?php
header('Content-Type: application/json');

try {
  // Ensure the directory path is correct
  $directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;

  // Save the uploaded files
  Input::save_files($directorio, $_FILES['archivos_ejemplo'], $id_rfq);

  // Partner sync: a re-upload under a shared file's name is an amendment — notify. Never fatal.
  try {
    Conexion::abrir_conexion();
    PartnerSyncService::reconcile(Conexion::obtener_conexion(), (int)$id_rfq);
    Conexion::cerrar_conexion();
  } catch (Throwable $e) {
    error_log('Partner reconcile after upload failed: ' . $e->getMessage());
  }

  // Return success result
  echo json_encode(array('result' => '1'));
} catch (Exception $e) {
  // Return error result with the error message
  echo json_encode(array(
    'result' => '0',
    'error' => $e->getMessage()
  ));
}
