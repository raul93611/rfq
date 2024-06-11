<?php
if (isset($_POST['submit'])) {
  // Function to sanitize input
  function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
  }

  // Validate and sanitize inputs
  $master = isset($_POST['master']) ? sanitize_input($_POST['master']) : '';
  $id_rfq = isset($_POST['id_rfq']) ? sanitize_input($_POST['id_rfq']) : '';

  if (empty($master) || empty($id_rfq)) {
    // Handle the error appropriately (e.g., log it, show a message, etc.)
    die('Both master and ID RFQ are required');
  }

  try {
    // Open database connection
    Conexion::abrir_conexion();

    // Link the quote
    RepositorioRfq::linkQuote(Conexion::obtener_conexion(), $master, $id_rfq);

    // Close database connection
    Conexion::cerrar_conexion();

    // Redirect to the appropriate page
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq);
  } catch (Exception $e) {
    // Handle exceptions
    die('An error occurred while processing your request');
  }
}
