<?php
/**
 * Integration test for the Import Items Enhancements feature
 * (see features/import-items-enhancements.md).
 *
 * Covers:
 *   - parseProviderPairs(): skips a pair with a blank Name, defaults Price to 0 when
 *     Price is blank/non-numeric but Name is present, reads up to 5 pairs.
 *   - processCsv(): reads the 5 provider Name/Price pairs (columns 10-19) alongside the
 *     unchanged existing 10 item columns (0-9).
 *   - Replace mode's delete cascade: RepositorioItem::delete_item() alone does NOT clean
 *     up `subitems` (the pre-existing gap this feature closes) — deleting subitems via
 *     RepositorioSubitem::delete_subitem() first, then the item, removes everything
 *     (subitems, provider_subitems, provider, item).
 *   - import_items_template.php streams a 20-column header-only xlsx (existing 10 item
 *     columns + 5 new Provider Name/Price pairs appended at the end, unchanged
 *     positions 0-9 so already-filled sheets keep lining up).
 *
 * DB rows are transaction-isolated (ROLLBACK).
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/import_items_test.php
 */

$root = __DIR__ . '/../../';
// import_items.php / import_items_template.php `require 'vendor/autoload.php'` with a
// relative path — chdir so that resolves regardless of the invoking shell's cwd.
chdir($root);

require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Item.inc.php';
require_once $root . 'app/Quote/RepositorioItem.inc.php';
require_once $root . 'app/Quote/Provider.inc.php';
require_once $root . 'app/Quote/RepositorioProvider.inc.php';
require_once $root . 'app/Quote/Subitem.inc.php';
require_once $root . 'app/Quote/RepositorioSubitem.inc.php';
require_once $root . 'app/Room/Room.inc.php';
require_once $root . 'app/Room/RoomRepository.inc.php';
// Top-level POST-handling block no-ops under CLI ($_SERVER['REQUEST_METHOD'] isn't
// 'POST'), so this only pulls in parseProviderPairs()/processCsv()/processExcel().
require_once $root . 'scripts/quote/import_items.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

function insertQuote($conexion, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'IITEST',
    'email_code' => 'II-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => '01/01/1999', 'end_date' => '01/01/1999', 'status' => 0, 'completado' => 0,
    'comments' => 'No comments', 'award' => 0, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 0,
    'contract_number' => '', 'total_price' => 0,
    'invoice' => 0, 'submitted_invoice' => 0, 'deleted' => 0, 'name' => 'Import Items Test',
  ], $o);
  $cols = array_keys($f);
  $ph = array_map(fn($c) => ':' . $c, $cols);
  $sql = 'INSERT INTO rfq (' . implode(',', $cols) . ') VALUES (' . implode(',', $ph) . ')';
  $stmt = $conexion->prepare($sql);
  foreach ($f as $c => $v) { $stmt->bindValue(':' . $c, $v); }
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

function insertItem($conexion, $idRfq) {
  $sql = "INSERT INTO item (id_rfq, id_usuario, provider_menor, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional)
          VALUES (:id_rfq, 1, 0, 'B', 'B', 'PN', 'PN', 'D', 'D', 1, 10, 10, '', '', '')";
  $stmt = $conexion->prepare($sql);
  $stmt->bindValue(':id_rfq', $idRfq, PDO::PARAM_INT);
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

function insertSubitem($conexion, $idItem) {
  $sql = "INSERT INTO subitems (id_item, provider_menor, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional)
          VALUES (:id_item, 0, 'SB', 'SB', 'SPN', 'SPN', 'SD', 'SD', 1, 5, 5, '', '', '')";
  $stmt = $conexion->prepare($sql);
  $stmt->bindValue(':id_item', $idItem, PDO::PARAM_INT);
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

function insertProviderSubitem($conexion, $idSubitem) {
  $sql = "INSERT INTO provider_subitems (id_subitem, provider, price) VALUES (:id_subitem, 'Sub Provider', 4.5)";
  $stmt = $conexion->prepare($sql);
  $stmt->bindValue(':id_subitem', $idSubitem, PDO::PARAM_INT);
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

function countRows($conexion, $table, $column, $id) {
  $stmt = $conexion->prepare("SELECT COUNT(*) FROM $table WHERE $column = :id");
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  return (int)$stmt->fetchColumn();
}

/* ================= parseProviderPairs() ================= */
echo "[parseProviderPairs]\n";
$pad = ['b','pn','d','pb','ppn','pd','1','c','w','room']; // columns 0-9, irrelevant here

check(
  'blank Name skips the pair entirely',
  [],
  parseProviderPairs(array_merge($pad, ['', '50']), 10)
);
check(
  'Name present + non-numeric Price defaults Price to 0',
  [['name' => 'Acme', 'price' => 0]],
  parseProviderPairs(array_merge($pad, ['Acme', 'n/a']), 10)
);
check(
  'Name present + blank Price defaults Price to 0',
  [['name' => 'Acme', 'price' => 0]],
  parseProviderPairs(array_merge($pad, ['Acme', '']), 10)
);
check(
  'Name present + valid numeric Price is kept as-is',
  [['name' => 'Acme', 'price' => '42.50']],
  parseProviderPairs(array_merge($pad, ['Acme', '42.50']), 10)
);
check(
  'reads all 5 pairs, skipping a blank one in the middle',
  [
    ['name' => 'P1', 'price' => '1'],
    ['name' => 'P3', 'price' => 0],
    ['name' => 'P5', 'price' => '5'],
  ],
  parseProviderPairs(array_merge($pad, ['P1', '1', '', '2', 'P3', 'x', '', '', 'P5', '5']), 10)
);

/* ================= processCsv() with provider columns ================= */
echo "\n[processCsv reads provider columns 10-19 alongside the unchanged 0-9]\n";
$tmpCsv = tempnam(sys_get_temp_dir(), 'ii_csv');
$csv = "Brand,Part Number,Description,Proposal Brand,Proposal Part Number,Proposal Description,Quantity,Comments,Website,Room,Provider 1 Name,Provider 1 Price,Provider 2 Name,Provider 2 Price,Provider 3 Name,Provider 3 Price,Provider 4 Name,Provider 4 Price,Provider 5 Name,Provider 5 Price\n"
  . "Acme,PN-1,A part,Acme,PN-1,A part,3,,http://example.com,Room A,CDW,100,B&H,95.5,,,,,,\n";
file_put_contents($tmpCsv, $csv);
$rows = processCsv($tmpCsv);
unlink($tmpCsv);

check('parses exactly one data row', 1, count($rows));
check('existing brand column (0) unaffected', 'Acme', $rows[0]['brand']);
check('existing room column (9) unaffected', 'Room A', $rows[0]['room']);
check('two providers parsed from columns 10-13', 2, count($rows[0]['providers']));
check('first provider name/price', ['name' => 'CDW', 'price' => '100'], $rows[0]['providers'][0]);
check('second provider name/price', ['name' => 'B&H', 'price' => '95.5'], $rows[0]['providers'][1]);

/* ================= Replace mode's delete cascade ================= */
echo "\n[Replace mode delete cascade: subitems must be cleared, not just the item]\n";
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$conexion->beginTransaction();

try {
  $idRfq = insertQuote($conexion);

  // Baseline: delete_item() ALONE — the pre-existing gap this feature fixes. `subitems.id_item`
  // has a FK to `item.id`, so this isn't silent orphaning — it's an uncaught FK-violation
  // exception, which would have crashed Replace mode outright on any item with a subitem.
  $idItemA = insertItem($conexion, $idRfq);
  $idSubitemA = insertSubitem($conexion, $idItemA);
  insertProviderSubitem($conexion, $idSubitemA);
  $threw = false;
  try {
    RepositorioItem::delete_item($conexion, $idItemA);
  } catch (Exception $e) {
    $threw = true;
  }
  check('gap reproduced: delete_item() alone throws on an item with a subitem (FK violation)', true, $threw);
  check('item survives the failed delete', 1, countRows($conexion, 'item', 'id', $idItemA));
  // Clean up so it doesn't leak into the next scenario.
  RepositorioSubitem::delete_subitem($conexion, $idSubitemA);
  RepositorioItem::delete_item($conexion, $idItemA);

  // Fixed sequence: delete each subitem first (import_items.php's replace-mode order),
  // then the item — nothing orphaned.
  $idItemB = insertItem($conexion, $idRfq);
  $idSubitemB = insertSubitem($conexion, $idItemB);
  $idProvSubB = insertProviderSubitem($conexion, $idSubitemB);
  $providerB = new Provider('', $idItemB, 'Item Provider', 12.0);
  RepositorioProvider::insertar_provider($conexion, $providerB);

  foreach (RepositorioSubitem::obtener_subitems_por_id_item($conexion, $idItemB) as $subitem) {
    RepositorioSubitem::delete_subitem($conexion, $subitem->obtener_id());
  }
  RepositorioItem::delete_item($conexion, $idItemB);

  check('fixed sequence: subitems cleared', 0, countRows($conexion, 'subitems', 'id_item', $idItemB));
  check('fixed sequence: provider_subitems cleared (cascaded by delete_subitem)', 0, countRows($conexion, 'provider_subitems', 'id_subitem', $idSubitemB));
  check('fixed sequence: item-level providers cleared (cascaded by delete_item)', 0, countRows($conexion, 'provider', 'id_item', $idItemB));
  check('fixed sequence: item itself removed', 0, countRows($conexion, 'item', 'id', $idItemB));
} finally {
  $conexion->rollBack();
  Conexion::cerrar_conexion();
}

/* ================= import_items_template.php ================= */
echo "\n[import_items_template.php streams a 20-column header-only xlsx]\n";
// Run as a genuinely separate process (not an in-process include) so its header() calls
// don't trip "headers already sent" against this script's own earlier stdout output.
$xlsxBytes = shell_exec('php ' . escapeshellarg($root . 'scripts/quote/import_items_template.php'));

$tmpXlsx = tempnam(sys_get_temp_dir(), 'ii_tpl') . '.xlsx';
file_put_contents($tmpXlsx, $xlsxBytes);
$sheet = IOFactory::load($tmpXlsx)->getActiveSheet();
$headerRow = $sheet->toArray()[0];
unlink($tmpXlsx);

check('header row has 20 columns', 20, count(array_filter($headerRow, fn($c) => $c !== null && $c !== '')));
check('column 0 (Brand) unchanged', 'Brand', $headerRow[0]);
check('column 9 (Room) unchanged — same position processCsv()/processExcel() already read', 'Room', $headerRow[9]);
check('column 10 starts the new Provider pairs', 'Provider 1 Name', $headerRow[10]);
check('column 19 is the last Provider pair', 'Provider 5 Price', $headerRow[19]);
check('only one data row (header only, no sample row)', 1, count($sheet->toArray()));

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
