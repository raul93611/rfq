<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

function createSpreadsheet() {
  $spreadsheet = new Spreadsheet();
  $activeWorksheet = $spreadsheet->getActiveSheet();

  // Setup headers
  $headers = [
    'A2' => 'FULFILLMENT DATE', 'B2' => 'PROPOSAL #', 'C2' => 'DESIGNATED USER',
    'D2' => 'CHANNEL', 'E2' => 'CODE', 'F2' => 'TYPE OF BID', 'G2' => 'CONTRACT NUMBER',
    'H2' => 'TOTAL COST', 'I2' => 'TOTAL PRICE', 'J2' => 'PROFIT', 'K2' => 'PROFIT(%)',
    'L2' => 'TOTAL COST', 'M2' => 'TOTAL PRICE', 'N2' => 'PROFIT', 'O2' => 'PROFIT(%)', 'P2' => 'TYPE', 'Q2' => 'SET ASIDE', 'R2' => 'STATE'
  ];

  $activeWorksheet->mergeCells('H1:K1');
  $activeWorksheet->getStyle('H1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
  $activeWorksheet->setCellValue('H1', 'QUOTE');

  $activeWorksheet->mergeCells('L1:O1');
  $activeWorksheet->getStyle('L1:O1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
  $activeWorksheet->setCellValue('L1', 'RE-QUOTE');

  foreach ($headers as $cell => $header) {
    $activeWorksheet->setCellValue($cell, $header);
    if (in_array($cell, ['H2', 'I2', 'J2', 'K2'])) {
      $activeWorksheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
    }
    if (in_array($cell, ['L2', 'M2', 'N2', 'O2'])) {
      $activeWorksheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
    }
  }

  return $spreadsheet;
}

function fetchAndFillData($spreadsheet, $type, $quarter, $month, $year) {
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();
    ExcelRepository::fulfillmentReport($conexion, $type, $quarter, $month, $year, $spreadsheet->getActiveSheet());
  } catch (Exception $e) {
    echo 'Error fetching data: ' . $e->getMessage();
  } finally {
    Conexion::cerrar_conexion();
  }
}

function outputSpreadsheet($spreadsheet) {
  $writer = new Xlsx($spreadsheet);

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="FulfillmentReport.xlsx"');
  header('Cache-Control: max-age=0');

  $writer->save('php://output');
}

// Ensure required POST parameters are set
if (isset($_POST['type'], $_POST['year'])) {
  $spreadsheet = createSpreadsheet();
  fetchAndFillData($spreadsheet, $_POST['type'], $_POST['quarter'] ?? null, $_POST['month'] ?? null, $_POST['year']);
  outputSpreadsheet($spreadsheet);
} else {
  echo 'Error: Missing required POST parameters.';
}
