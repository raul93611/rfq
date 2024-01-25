<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

$activeWorksheet->setCellValue('A1', 'INVOICE DATE');
$activeWorksheet->setCellValue('B1', 'INVOICE');
$activeWorksheet->setCellValue('C1', 'TYPE OF CONTRACT');
$activeWorksheet->setCellValue('D1', 'INVOICE AMOUNT');
$activeWorksheet->setCellValue('E1', 'SALES COMMISSION');
$activeWorksheet->setCellValue('F1', 'TOTAL PROFIT($)');
$activeWorksheet->setCellValue('G1', 'TOTAL PROFIT(%)');
$activeWorksheet->setCellValue('H1', 'INVOICE ACCEPTANCE');

Conexion::abrir_conexion();
ExcelRepository::projectionsMonth(Conexion::obtener_conexion(), $id, $activeWorksheet);
Conexion::cerrar_conexion();

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Projection.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
