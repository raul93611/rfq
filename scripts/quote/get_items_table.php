<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  exit;
}

header('Content-Type: text/html; charset=utf-8');

// Renders the whole items section (header, controls bar, table — or the empty state),
// same as edicion_cotizacion_recuperada.inc.php's .items-section-wrapper. Rendering rows
// alone couldn't create/remove the surrounding <table>/<tbody id="items"> when the item
// count crossed the 0-to-1 boundary — on an empty quote that table doesn't exist yet, so
// $('#items').html(...) silently no-opped and the first added item never appeared until
// a full page reload. escribir_items() renders the empty state itself (.it-card stays,
// only the table area swaps for a placeholder) when there are no items.
RepositorioItem::escribir_items($id_rfq);
