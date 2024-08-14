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
    'A1' => 'PROPOSAL #',
    'B1' => 'AWARD DATE',
    'C1' => 'CONTRACT NUMBER',
    'D1' => 'CODE',
    'E1' => 'DESIGNATED USER',
    'F1' => 'CHANNEL',
    'G1' => 'TYPE OF BID',
    'H1' => 'TOTAL COST',
    'I1' => 'TOTAL PRICE',
    'J1' => 'PROFIT',
    'K1' => 'PROFIT (%)',
    'L1' => 'TYPE'
  ];

  foreach ($headers as $cell => $header) {
    $activeWorksheet->setCellValue($cell, $header);
    if (in_array($cell, ['H1', 'I1', 'J1', 'K1'])) {
      $activeWorksheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
    }
  }

  return $spreadsheet;
}

function fetchAndFillData($spreadsheet, $type, $quarter, $month, $year) {
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();
    ExcelRepository::awardReport($conexion, $type, $quarter, $month, $year, $spreadsheet->getActiveSheet());
  } catch (Exception $e) {
    echo 'Error fetching data: ' . $e->getMessage();
  } finally {
    Conexion::cerrar_conexion();
  }
}

function outputSpreadsheet($spreadsheet) {
  $writer = new Xlsx($spreadsheet);

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="AwardReport.xlsx"');
  header('Cache-Control: max-age=0');

  $writer->save('php://output');
}

// Ensure required POST parameters are set
if (isset($_POST['type'])) {
  $type = $_POST['type'];
  $year = $_POST['year'] ?? null;
  $quarter = $_POST['quarter'] ?? null;
  $month = $_POST['month'] ?? null;

  $isValid = false;

  switch ($type) {
    case 'monthly':
      $isValid = isset($month, $year);
      break;
    case 'quarterly':
      $isValid = isset($quarter, $year);
      break;
    case 'yearly':
      $isValid = isset($year);
      break;
  }

  if ($isValid) {
    $spreadsheet = createSpreadsheet();
    fetchAndFillData($spreadsheet, $type, $quarter, $month, $year);
    outputSpreadsheet($spreadsheet);
  } else {
    echo 'Error: Missing required POST parameters for ' . $type . ' report.';
  }
} else {
  echo 'Error: Missing report type.';
}
