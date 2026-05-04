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
    'A1' => 'PROPOSAL #',
    'B1' => 'DESIGNATED USER',
    'C1' => 'CODE',
    'D1' => 'TYPE OF BID',
    'E1' => 'COMMENTS',
    'F1' => 'ISSUE DATE'
  ];

  foreach ($headers as $cell => $header) {
    $activeWorksheet->setCellValue($cell, $header);
  }

  return $spreadsheet;
}

function fetchAndFillData($spreadsheet, $type, $quarter, $month, $year) {
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();
    ExcelRepository::notSubmittedReport($conexion, $type, $quarter, $month, $year, $spreadsheet->getActiveSheet());
  } catch (Exception $e) {
    echo 'Error fetching data: ' . $e->getMessage();
  } finally {
    Conexion::cerrar_conexion();
  }
}

function outputSpreadsheet($spreadsheet) {
  $writer = new Xlsx($spreadsheet);

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="NotSubmittedReport.xlsx"');
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