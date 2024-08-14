<?php
// Close the session
ControlSesion::cerrar_sesion();

// Check if the session was successfully closed
if (!ControlSesion::sesion_iniciada()) {
  // Redirect to the server URL
  Redireccion::redirigir(SERVIDOR);
} else {
  // Handle the error if the session could not be closed
  echo 'Error: Unable to close the session.';
}
