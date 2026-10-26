<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  exit;
}

header('Content-Type: text/html; charset=utf-8');

// Read-only preview for the Invoice modal: the breakdown the server would save right now.
// Nothing is written; the real calculation re-runs when the invoice is saved.
Conexion::abrir_conexion();
$commission = SalesCommissionCalculator::forQuote(Conexion::obtener_conexion(), (int)$id_rfq);
Conexion::cerrar_conexion();

include 'plantillas/quote/modals/commission_breakdown.inc.php';
