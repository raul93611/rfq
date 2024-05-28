<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

function fetchData($id_rfq) {
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    $providers_name = RepositorioRfq::get_all_providers_name($conexion, $id_rfq);
    $requote = ReQuoteRepository::get_re_quote_by_id_rfq($conexion, $id_rfq);
    $requote_providers_name = ReQuoteRepository::get_all_providers_name($conexion, $requote->get_id());

    return [
      'providers_name' => $providers_name,
      'requote' => $requote,
      'requote_providers_name' => $requote_providers_name
    ];
  } catch (Exception $e) {
    echo 'Error fetching data: ' . $e->getMessage();
    return null;
  } finally {
    Conexion::cerrar_conexion();
  }
}

function createSpreadsheet($data) {
  $spreadsheet = new Spreadsheet();
  $activeWorksheet = $spreadsheet->getActiveSheet();

  // Setup headers
  $headers = [
    'QUOTE' => ['column_start' => 6, 'color' => '4be3e3', 'names' => $data['providers_name']],
    'RE-QUOTE' => ['column_start' => 6 + count($data['providers_name']), 'color' => '03befc', 'names' => $data['requote_providers_name']]
  ];

  foreach ($headers as $title => $header) {
    $start = $header['column_start'];
    $end = $start + count($header['names']) - 1;
    $range = Coordinate::stringFromColumnIndex($start) . '1:' . Coordinate::stringFromColumnIndex($end) . '1';
    $activeWorksheet->mergeCells($range);
    $activeWorksheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($header['color']);
    $activeWorksheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->setCellValue(Coordinate::stringFromColumnIndex($start) . '1', $title);
  }

  // Setup columns
  $columns = [
    ['#', 'ed9191'],
    ['PROJECT SPECIFICATIONS', 'ed9191'],
    ['E-LOGIC PROPOSAL', 'ed9191'],
    ['PART NUMBER', 'e8c92e'],
    ['QUANTITY', 'c2e34b']
  ];

  $x = 1;
  foreach ($columns as $column) {
    $activeWorksheet->getStyle(Coordinate::stringFromColumnIndex($x) . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($column[1]);
    $activeWorksheet->setCellValue(Coordinate::stringFromColumnIndex($x) . '2', $column[0]);
    $x++;
  }

  foreach (['providers_name', 'requote_providers_name'] as $key) {
    $color = $key === 'providers_name' ? '4be3e3' : '03befc';
    foreach ($data[$key] as $provider_name) {
      $activeWorksheet->getStyle(Coordinate::stringFromColumnIndex($x) . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($color);
      $activeWorksheet->setCellValue(Coordinate::stringFromColumnIndex($x) . '2', strtoupper($provider_name));
      $x++;
    }
  }

  $extraColumns = [
    ['PRICE FOR CLIENT', '668fe8'],
    ['TOTAL PRICE', '668fe8']
  ];

  foreach ($extraColumns as $column) {
    $activeWorksheet->getStyle(Coordinate::stringFromColumnIndex($x) . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($column[1]);
    $activeWorksheet->setCellValue(Coordinate::stringFromColumnIndex($x) . '2', $column[0]);
    $x++;
  }

  return $spreadsheet;
}

function outputSpreadsheet($spreadsheet) {
  $writer = new Xlsx($spreadsheet);

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="ExcelItemsTable.xlsx"');
  header('Cache-Control: max-age=0');

  $writer->save('php://output');
}

// Assuming $id_rfq is already declared and coming from another file
if (isset($id_rfq)) {
  $data = fetchData($id_rfq);
  if ($data) {
    $spreadsheet = createSpreadsheet($data);
    Conexion::abrir_conexion();
    ExcelRepository::print_items(Conexion::obtener_conexion(), $spreadsheet->getActiveSheet(), $data['providers_name'], $data['requote_providers_name'], $data['requote'], $id_rfq);
    Conexion::cerrar_conexion();
    outputSpreadsheet($spreadsheet);
  }
} else {
  echo 'Error: No RFQ ID provided.';
}
