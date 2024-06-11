<?php
if (isset($_POST['send'])) {
  // Ensure necessary POST variable is set
  $slaveId = $_POST['slave'] ?? null;
  if (!$slaveId) {
    die('Error: Slave ID is not set.');
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
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $slaveId);
  } catch (Exception $e) {
    // Handle any exceptions that occur
    if (isset($conexion)) {
      Conexion::cerrar_conexion();
    }
    die('An error occurred: ' . $e->getMessage());
  }
}
