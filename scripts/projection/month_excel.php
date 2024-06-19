<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
  // Initialize spreadsheet
  $spreadsheet = new Spreadsheet();
  $activeWorksheet = $spreadsheet->getActiveSheet();

  // Set spreadsheet headers
  $headers = [
    'A1' => 'INVOICE DATE',
    'B1' => 'INVOICE',
    'C1' => 'TYPE OF CONTRACT',
    'D1' => 'INVOICE AMOUNT',
    'E1' => 'SALES COMMISSION',
    'F1' => 'TOTAL PROFIT($)',
    'G1' => 'TOTAL PROFIT(%)',
    'H1' => 'INVOICE ACCEPTANCE'
  ];

  foreach ($headers as $cell => $value) {
    $activeWorksheet->setCellValue($cell, $value);
  }

  // Open database connection
  Conexion::abrir_conexion();

  // Fetch data and populate spreadsheet
  ExcelRepository::projectionsMonth(Conexion::obtener_conexion(), $id, $activeWorksheet);

  // Close database connection
  Conexion::cerrar_conexion();

  // Prepare spreadsheet for download
  $writer = new Xlsx($spreadsheet);

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Projection.xlsx"');
  header('Cache-Control: max-age=0');

  // Save the spreadsheet to the output stream
  $writer->save('php://output');
} catch (Exception $e) {
  // Handle exceptions and display error message
  echo "Error: " . $e->getMessage();
}
