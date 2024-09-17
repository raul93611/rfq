<?php
if (isset($_POST['iniciar_sesion'])) {
  // Sanitize user inputs
  $nombre_usuario = trim(htmlspecialchars($_POST['nombre_usuario'], ENT_QUOTES, 'UTF-8'));
  $password = trim(htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'));

  try {
    // Open connection
    Conexion::abrir_conexion();

    // Validate login credentials
    $validador = new ValidadorLogin($nombre_usuario, $password, Conexion::obtener_conexion());
  } catch (Exception $e) {
    // Handle connection error (without printing errors here)
    exit;
  } finally {
    // Ensure the connection is always closed
    Conexion::cerrar_conexion();
  }

  // Check for validation errors or null user
  if ($validador->obtener_error() !== '' || is_null($validador->obtener_usuario())) {
    return; // Stop further processing if validation fails
  }

  // If validation passed, start session and redirect
  ControlSesion::iniciar_sesion($validador->obtener_usuario());
  Redireccion::redirigir1(ALL_TASKS);
}
