<?php
/**
 * Integration test for the Internal Due Date required field (feature: internal-due-date.md).
 *
 * Covers ValidadorCotizacionRegistro: blank Internal Due Date fails validation with the
 * same "Must be fill out." message End Date already uses; filled passes and the value is
 * retained. The Table-view preset filter itself is covered in pipeline_table_test.php.
 *
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/internal_due_date_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';
require_once $root . 'app/Quote/ValidadorCotizacion.inc.php';
require_once $root . 'app/Quote/ValidadorCotizacionRegistro.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

echo "[ValidadorCotizacionRegistro — Internal Due Date required]\n";

$blank = new ValidadorCotizacionRegistro(
  $conexion, 'IDD-' . uniqid(), '01/01/2099', '01/02/2099', '', 'IT', 'RSantos', 'GSA-Buy'
);
check('blank internal_due_date -> "Must be fill out." error', 'Must be fill out.', $blank->obtener_error_internal_due_date());
check('blank internal_due_date -> registro_cotizacion_valida() false', false, $blank->registro_cotizacion_valida());

$filled = new ValidadorCotizacionRegistro(
  $conexion, 'IDD-' . uniqid(), '01/01/2099', '01/02/2099', '01/15/2099', 'IT', 'RSantos', 'GSA-Buy'
);
check('filled internal_due_date -> no error', '', $filled->obtener_error_internal_due_date());
check('filled internal_due_date -> value retained', '01/15/2099', $filled->obtener_internal_due_date());
check('filled internal_due_date -> registro_cotizacion_valida() true', true, $filled->registro_cotizacion_valida());

// No cross-field constraint against End Date (matches Issue Date/End Date convention).
$anyOrder = new ValidadorCotizacionRegistro(
  $conexion, 'IDD-' . uniqid(), '06/01/2099', '01/01/2099', '01/01/2050', 'IT', 'RSantos', 'GSA-Buy'
);
check('internal_due_date has no cross-field date constraint', true, $anyOrder->registro_cotizacion_valida());

Conexion::cerrar_conexion();

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
