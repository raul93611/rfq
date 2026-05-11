<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  exit;
}
header('Content-Type: text/html; charset=utf-8');
include_once 'forms/quote/edicion_provider_vacio.inc.php';
