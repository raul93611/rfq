<?php
/**
 * Integration test for: Designated User must stay editable after a quote is
 * Completed/Submitted+ (bugs/designated-user-locked-after-completed.md).
 *
 * Input::print_designated_user() used to swap the editable <select> for a readonly
 * <input> once $quote->obtener_completado() or $quote->obtener_status() was truthy —
 * permanently locking reassignment from Completed onward. This covers:
 *   • Completed-but-not-submitted quote -> dropdown still rendered, no readonly attr,
 *     correct user pre-selected
 *   • Submitted quote (status=1) -> same
 *   • Not-yet-completed quote -> unaffected (already worked, sanity check)
 *
 * Transaction-isolated (ROLLBACK).
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/designated_user_editable_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/User/Usuario.inc.php';
require_once $root . 'app/User/RepositorioUsuario.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/Utilities/Input.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

Conexion::abrir_conexion();
$c = Conexion::obtener_conexion();
$c->beginTransaction();

try {
  $uname = 'du_test_' . uniqid();
  $stmt = $c->prepare("INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status)
                       VALUES (:u, 'x', 'Designated', 'Tester', '3', :e, 1)");
  $stmt->execute([':u' => $uname, ':e' => $uname . '@test.local']);
  $userId = (int)$c->lastInsertId();

  $render = function ($fields) {
    $quote = new Rfq($fields);
    ob_start();
    Input::print_designated_user($quote);
    return ob_get_clean();
  };

  // Completed, not yet submitted — the exact scenario from the bug report.
  $completedHtml = $render(['completado' => 1, 'status' => 0, 'usuario_designado' => $userId]);
  check('Completed quote renders a <select>', true, strpos($completedHtml, '<select') !== false);
  check('Completed quote has no readonly input', false, strpos($completedHtml, 'readonly') !== false);
  check('Completed quote pre-selects the designated user', true, strpos($completedHtml, 'selected') !== false && strpos($completedHtml, $uname) !== false);

  // Submitted (status=1) — further along the lifecycle, must also stay editable.
  $submittedHtml = $render(['completado' => 1, 'status' => 1, 'usuario_designado' => $userId]);
  check('Submitted quote renders a <select>', true, strpos($submittedHtml, '<select') !== false);
  check('Submitted quote has no readonly input', false, strpos($submittedHtml, 'readonly') !== false);

  // Not completed — sanity check the existing editable path is untouched.
  $freshHtml = $render(['completado' => 0, 'status' => 0, 'usuario_designado' => $userId]);
  check('Not-yet-completed quote renders a <select>', true, strpos($freshHtml, '<select') !== false);
  check('Not-yet-completed quote has no readonly input', false, strpos($freshHtml, 'readonly') !== false);

} finally {
  $c->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
