<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

function fetchQuoteData($id_rfq) {
  Conexion::abrir_conexion();
  $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
  $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
  Conexion::cerrar_conexion();
  return [$quote, $re_quote];
}

function setCellValueWithStyle($worksheet, $column, $row, $value, $color) {
  $cell = $column . $row;
  $worksheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($color);
  $worksheet->setCellValue($cell, $value);
}

function createSpreadsheet($quote, $re_quote) {
  $spreadsheet = new Spreadsheet();
  $worksheet = $spreadsheet->getActiveSheet();

  // Header values and styles
  $headers = ['PROPOSAL #', 'CONTRACT NUMBER'];
  $headerColor = 'ed9191';
  $column = 'A';

  foreach ($headers as $header) {
    setCellValueWithStyle($worksheet, $column, 1, $header, $headerColor);
    $column++;
  }

  // Quote values
  $worksheet->setCellValue('A2', $quote->obtener_id());
  $worksheet->setCellValue('B2', $quote->obtener_contract_number());

  // Tracking headers
  $trackingHeaders = [
    '#', 'PROJECT ESPC', 'QTY(ORDERED)', 'QTY(SHIPPED)', 'CARRIER',
    'TRACKING #', 'DELIVERY DATE', 'DUE DATE', 'SIGNED BY', 'COMMENT'
  ];
  $row = 4;
  $column = 'A';

  foreach ($trackingHeaders as $header) {
    setCellValueWithStyle($worksheet, $column, $row, $header, $headerColor);
    $column++;
  }

  // Print tracking data
  Conexion::abrir_conexion();
  ExcelRepository::print_tracking(Conexion::obtener_conexion(), $worksheet, $quote, $re_quote);
  Conexion::cerrar_conexion();

  return $spreadsheet;
}

function sendSpreadsheetToBrowser($spreadsheet, $filename) {
  $writer = new Xlsx($spreadsheet);
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="' . $filename . '"');
  header('Cache-Control: max-age=0');
  $writer->save('php://output');
}

try {
  list($quote, $re_quote) = fetchQuoteData($id_rfq);
  $spreadsheet = createSpreadsheet($quote, $re_quote);
  sendSpreadsheetToBrowser($spreadsheet, 'TrackingProposal' . $quote->obtener_id() . '.xlsx');
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}
