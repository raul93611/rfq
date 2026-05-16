<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  exit;
}

header('Content-Type: text/html; charset=utf-8');
RepositorioItem::escribir_items_rows($id_rfq);
