<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

$activeWorksheet->setCellValue('A1', 'PROPOSAL');
$activeWorksheet->setCellValue('B1', 'PROVIDER');
$activeWorksheet->setCellValue('C1', 'REAL COST');
$activeWorksheet->setCellValue('D1', 'PAYMENT TERM');
$activeWorksheet->setCellValue('E1', 'TRANSACTION DATE');

Conexion::abrir_conexion();
ExcelRepository::accountsPayableFulfillmentReport(Conexion::obtener_conexion(), $_POST['type'], $_POST['quarter'], $_POST['month'], $_POST['year'], $activeWorksheet);
Conexion::cerrar_conexion();

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="AccountsPayableFulfillmentReport.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
