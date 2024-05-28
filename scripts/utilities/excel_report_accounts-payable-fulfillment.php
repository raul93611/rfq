<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function createSpreadsheet() {
  $spreadsheet = new Spreadsheet();
  $activeWorksheet = $spreadsheet->getActiveSheet();

  // Setup headers
  $headers = ['A1' => 'PROPOSAL', 'B1' => 'PROVIDER', 'C1' => 'REAL COST', 'D1' => 'PAYMENT TERM', 'E1' => 'TRANSACTION DATE'];
  foreach ($headers as $cell => $header) {
    $activeWorksheet->setCellValue($cell, $header);
  }

  return $spreadsheet;
}

function fetchAndFillData($spreadsheet, $type, $quarter, $month, $year) {
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    ExcelRepository::accountsPayableFulfillmentReport($conexion, $type, $quarter, $month, $year, $spreadsheet->getActiveSheet());
  } catch (Exception $e) {
    echo 'Error fetching data: ' . $e->getMessage();
  } finally {
    Conexion::cerrar_conexion();
  }
}

function outputSpreadsheet($spreadsheet) {
  $writer = new Xlsx($spreadsheet);

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="AccountsPayableFulfillmentReport.xlsx"');
  header('Cache-Control: max-age=0');

  $writer->save('php://output');
}

// Ensure required POST parameters are set
if (isset($_POST['type'], $_POST['quarter'], $_POST['month'], $_POST['year'])) {
  $spreadsheet = createSpreadsheet();
  fetchAndFillData($spreadsheet, $_POST['type'], $_POST['quarter'], $_POST['month'], $_POST['year']);
  outputSpreadsheet($spreadsheet);
} else {
  echo 'Error: Missing required POST parameters.';
}
