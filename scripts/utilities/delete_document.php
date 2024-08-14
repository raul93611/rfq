<?php
header('Content-Type: application/json');

// Decode the file name and construct the file path
$decoded_file_name = urldecode($archivo);
$file_path = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq . '/' . $decoded_file_name;

// Initialize the response array
$response = array('result' => '0');

try {
  // Check if the file exists before attempting to delete it
  if (file_exists($file_path)) {
    if (unlink($file_path)) {
      // File successfully deleted, update the audit trail
      Conexion::abrir_conexion();
      $conexion = Conexion::obtener_conexion();
      AuditTrailRepository::document_updated($conexion, 'deleted', $decoded_file_name, $id_rfq);
      $response['result'] = '1';
    } else {
      // Print error if the file could not be deleted
      print "Error deleting file: $file_path";
    }
  } else {
    // Print error if the file does not exist
    print "File not found: $file_path";
  }
} catch (Exception $e) {
  // Print the exception message
  print "Exception: " . $e->getMessage();
} finally {
  // Ensure the database connection is closed
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
}

// Return the response as JSON
echo json_encode($response);
