<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

$activeWorksheet->setCellValue('A1', 'AWARD DATE');
$activeWorksheet->setCellValue('B1', 'PROPOSAL #');
$activeWorksheet->setCellValue('C1', 'CONTRACT NUMBER');
$activeWorksheet->setCellValue('D1', 'CODE');
$activeWorksheet->setCellValue('E1', 'DESIGNATED USER');
$activeWorksheet->setCellValue('F1', 'CHANNEL');
$activeWorksheet->setCellValue('G1', 'TYPE OF BID');
$activeWorksheet->getStyle('H1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('H1', 'TOTAL COST');
$activeWorksheet->getStyle('I1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('I1', 'TOTAL PRICE');
$activeWorksheet->getStyle('J1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('J1', 'PROFIT');
$activeWorksheet->getStyle('K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('K1', 'PROFIT (%)');
$activeWorksheet->setCellValue('L1', 'TYPE');

Conexion::abrir_conexion();
ExcelRepository::awardReport(Conexion::obtener_conexion(), $_POST['type'], $_POST['quarter'], $_POST['month'], $_POST['year'], $activeWorksheet);
Conexion::cerrar_conexion();

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="AwardReport.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
