<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

function createSpreadsheet() {
  $spreadsheet = new Spreadsheet();
  $activeWorksheet = $spreadsheet->getActiveSheet();

  // Setup merged cells and headers with styling
  $headers = [
    'A2' => 'PROPOSAL #', 'B2' => 'INVOICE DATE', 'C2' => 'CONTRACT NUMBER',
    'D2' => 'DESIGNATED USER', 'E2' => 'STATE', 'F2' => 'CLIENT',
    'G2' => 'TOTAL COST', 'H2' => 'TOTAL PRICE', 'I2' => 'PROFIT',
    'J2' => 'TOTAL COST', 'K2' => 'TOTAL PRICE', 'L2' => 'PROFIT RFQ',
    'M2' => 'PROFIT RFP', 'N2' => 'PROFIT %', 'O2' => 'TOTAL COST',
    'P2' => 'TOTAL PRICE', 'Q2' => 'PROFIT RFQ', 'R2' => 'PROFIT RFP',
    'S2' => 'PROFIT %', 'T2' => 'TYPE OF CONTRACT', 'U2' => 'SALES COMMISSION',
    'V2' => 'SALES COMMISSION AMOUNT'
  ];

  foreach ($headers as $cell => $header) {
    $activeWorksheet->setCellValue($cell, $header);
  }

  // Apply merged cells and styles
  $activeWorksheet->mergeCells('G1:I1');
  $activeWorksheet->getStyle('G1:I1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
  $activeWorksheet->setCellValue('G1', 'QUOTE');

  $activeWorksheet->mergeCells('J1:N1');
  $activeWorksheet->getStyle('J1:N1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
  $activeWorksheet->setCellValue('J1', 'RE-QUOTE');

  $activeWorksheet->mergeCells('O1:S1');
  $activeWorksheet->getStyle('O1:S1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('dc3545');
  $activeWorksheet->setCellValue('O1', 'FULFILLMENT');

  // Apply specific styles for the second row
  $styleCells = [
    'G2', 'H2', 'I2', 'J2', 'K2', 'L2', 'M2', 'N2', 'O2', 'P2', 'Q2', 'R2', 'S2'
  ];

  foreach ($styleCells as $cell) {
    $color = match (true) {
      str_starts_with($cell, 'G') || str_starts_with($cell, 'H') || str_starts_with($cell, 'I') => '197bff',
      str_starts_with($cell, 'J') || str_starts_with($cell, 'K') || str_starts_with($cell, 'L') || str_starts_with($cell, 'M') || str_starts_with($cell, 'N') => 'f0e716',
      str_starts_with($cell, 'O') || str_starts_with($cell, 'P') || str_starts_with($cell, 'Q') || str_starts_with($cell, 'R') || str_starts_with($cell, 'S') => 'dc3545',
      default => ''
    };
    $activeWorksheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($color);
  }

  return $spreadsheet;
}

function fetchAndFillData($spreadsheet, $type, $quarter, $month, $year) {
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();
    ExcelRepository::salesCommissionReport($conexion, $type, $quarter, $month, $year, $spreadsheet->getActiveSheet());
  } catch (Exception $e) {
    echo 'Error fetching data: ' . $e->getMessage();
  } finally {
    Conexion::cerrar_conexion();
  }
}

function outputSpreadsheet($spreadsheet) {
  $writer = new Xlsx($spreadsheet);

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="SalesCommissionReport.xlsx"');
  header('Cache-Control: max-age=0');

  $writer->save('php://output');
}

// Ensure required POST parameters are set
if (isset($_POST['type'])) {
  $spreadsheet = createSpreadsheet();
  fetchAndFillData($spreadsheet, $_POST['type'], $_POST['quarter'] ?? null, $_POST['month'] ?? null, $_POST['year'] ?? null);
  outputSpreadsheet($spreadsheet);
} else {
  echo 'Error: Missing required POST parameters.';
}
