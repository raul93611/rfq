<?php
try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Remove from SharePoint sheet before permanent deletion
  try {
    $quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
    if ($quote && $quote->getSheetRow()) {
      SheetSyncService::deleteRow($quote->getSheetRow());
      // Rows below shifted up by one — keep every other quote's stored pointer correct.
      SheetSyncRepository::shiftRowsAfterDelete($conexion, $quote->getSheetRow());
    }
  } catch (Exception $syncEx) {
    error_log('Sheet sync error on destroy: ' . $syncEx->getMessage());
  }

  RepositorioRfq::destroyQuote($conexion, $id_rfq);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Redirect based on the quote status
  Redireccion::redirigir(DELETED);
} catch (Exception $e) {
  // Ensure the connection is closed in case of an error
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  // Print the error message
  echo 'Error: ' . $e->getMessage();
  exit;
}
