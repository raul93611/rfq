<?php
if (isset($_POST['send'])) {
  // Ensure necessary POST variables are set
  $slaveId = $_POST['slave'] ?? null;
  $masterId = $_POST['master'] ?? null;

  if (!$slaveId || !$masterId) {
    die('Error: Required parameters are missing.');
  }

  try {
    // Open database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Perform the remove relation operation
    RepositorioRfq::remove_relation($conexion, $slaveId);

    // Close database connection
    Conexion::cerrar_conexion();

    // Redirect to the specified URL
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $masterId);
  } catch (Exception $e) {
    // Handle any exceptions that occur
    if (isset($conexion)) {
      Conexion::cerrar_conexion();
    }
    die('An error occurred: ' . $e->getMessage());
  }
}
