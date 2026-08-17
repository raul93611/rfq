<?php
/**
 * Integration test for: provider names with &, <, > double-escape on display
 * (bugs/provider-name-double-html-escaping.md).
 *
 * Root cause: guardar_add_provider.php / guardar_edit_provider.php /
 * guardar_add_provider_subitem.php / guardar_edit_provider_subitem.php sanitized the
 * incoming `provider` field with FILTER_SANITIZE_FULL_SPECIAL_CHARS, HTML-encoding it
 * BEFORE storage ("D&H" -> "D&amp;H"). RepositorioItem::renderProvidersList() then
 * escapes again at render time, turning the already-encoded value into
 * "D&amp;amp;H" in the HTML source (renders as literal "D&amp;H" on screen).
 *
 * The fix stores raw text and escapes only once, at render. This test reproduces the
 * old write-time double-encode (using filter_var directly — filter_input(INPUT_POST,
 * ...) is not exercisable from a CLI test, since there is no real request) and proves
 * it breaks the round trip, then proves the fixed write path (raw storage) round-trips
 * correctly through both the item and subitem provider list renderers.
 *
 * Transaction-isolated (ROLLBACK).
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/provider_name_escaping_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Provider.inc.php';
require_once $root . 'app/Quote/RepositorioProvider.inc.php';
require_once $root . 'app/Quote/RepositorioItem.inc.php';

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
  $uname = 'pn_test_' . uniqid();
  $stmt = $c->prepare("INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status)
                       VALUES (:u, 'x', 'Provider', 'Tester', '3', :e, 1)");
  $stmt->execute([':u' => $uname, ':e' => $uname . '@test.local']);
  $userId = (int)$c->lastInsertId();

  $stmt = $c->prepare("INSERT INTO rfq (id_usuario, usuario_designado, canal, email_code, type_of_bid,
      status, completado, comments, award, total_price, deleted, name)
      VALUES (:u, :u, 'FedBid', 'PN-TEST', 'IT', 0, 0, '', 0, 0, 0, 'Provider escaping test quote')");
  $stmt->execute([':u' => $userId]);
  $rfqId = (int)$c->lastInsertId();

  $stmt = $c->prepare("INSERT INTO item (id_rfq, id_usuario, provider_menor, brand, brand_project,
      part_number, part_number_project, description, description_project, quantity, unit_price,
      total_price, comments, website, additional)
      VALUES (:rfq, :u, 0, 'B', 'B', 'PN', 'PN', 'D', 'D', 1, 10.00, 10.00, '', '', '')");
  $stmt->execute([':rfq' => $rfqId, ':u' => $userId]);
  $itemId = (int)$c->lastInsertId();

  $insertAndRender = function ($storedName, $isSubitem) use ($c, $itemId) {
    $provider = new Provider('', $itemId, $storedName, 42.00);
    RepositorioProvider::insertar_provider($c, $provider);
    $providers = RepositorioProvider::obtener_providers_por_id_item($c, $itemId);
    // Only the just-inserted row matters for this render.
    $latest = end($providers);
    $html = RepositorioItem::renderProvidersList([$latest], $itemId, $isSubitem);
    // Clean up so successive calls in this test each render exactly one row.
    RepositorioProvider::delete_provider($c, $latest->obtener_id());
    return $html;
  };

  echo "[old (buggy) write path: FILTER_SANITIZE_FULL_SPECIAL_CHARS at write time]\n";
  $oldStored = filter_var('D&H', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // "D&amp;H" -- encoded before storage
  check('old sanitizer HTML-encodes at write time', 'D&amp;H', $oldStored);
  $oldHtml = $insertAndRender($oldStored, false);
  check('old path double-escapes: HTML source contains "D&amp;amp;H"', true, strpos($oldHtml, 'D&amp;amp;H') !== false);

  echo "\n[fixed write path: raw text stored, single escape at render]\n";
  $newStored = trim((string) filter_var('D&H', FILTER_UNSAFE_RAW)); // "D&H" -- untouched
  check('fixed sanitizer stores raw text', 'D&H', $newStored);

  $itemHtml = $insertAndRender($newStored, false);
  check('item provider list: single escape "D&amp;H" present', true, strpos($itemHtml, 'D&amp;H') !== false);
  check('item provider list: no double escape "D&amp;amp;H"', false, strpos($itemHtml, 'D&amp;amp;H') !== false);

  $subitemHtml = $insertAndRender($newStored, true);
  check('subitem provider list: single escape "D&amp;H" present', true, strpos($subitemHtml, 'D&amp;H') !== false);
  check('subitem provider list: no double escape "D&amp;amp;H"', false, strpos($subitemHtml, 'D&amp;amp;H') !== false);

  // <script> is a sharper canary than & alone -- a double-escape would leave the
  // literal string "&lt;script&gt;" visible as text instead of a harmless entity.
  $xssStored = trim((string) filter_var('<script>Acme</script>', FILTER_UNSAFE_RAW));
  $xssHtml = $insertAndRender($xssStored, false);
  check('angle brackets: single escape "&lt;script&gt;" present', true, strpos($xssHtml, '&lt;script&gt;') !== false);
  check('angle brackets: raw "<script>" never reaches the HTML source', false, strpos($xssHtml, '<script>') !== false);

} finally {
  $c->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
