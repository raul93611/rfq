<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

function createSpreadsheet() {
  $spreadsheet = new Spreadsheet();
  $activeWorksheet = $spreadsheet->getActiveSheet();

  // Define headers and styles
  $headers = [
    'A1' => 'SUBMITTED DATE', 'B1' => 'PROPOSAL #', 'C1' => 'CODE',
    'D1' => 'DESIGNATED USER', 'E1' => 'CHANNEL', 'F1' => 'TYPE OF BID',
    'G1' => 'TOTAL COST', 'H1' => 'TOTAL PRICE', 'I1' => 'PROFIT',
    'J1' => 'PROFIT (%)', 'K1' => 'TYPE'
  ];

  // Set header values and styles
  foreach ($headers as $cell => $header) {
    $activeWorksheet->setCellValue($cell, $header);
    if (in_array($cell, ['G1', 'H1', 'I1', 'J1'])) {
      $activeWorksheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
    }
  }

  return $spreadsheet;
}

function fetchAndFillData($spreadsheet, $type, $quarter, $month, $year) {
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();
    ExcelRepository::submittedReport($conexion, $type, $quarter, $month, $year, $spreadsheet->getActiveSheet());
  } catch (Exception $e) {
    echo 'Error fetching data: ' . $e->getMessage();
  } finally {
    Conexion::cerrar_conexion();
  }
}

function outputSpreadsheet($spreadsheet) {
  $writer = new Xlsx($spreadsheet);

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="SubmittedReport.xlsx"');
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
