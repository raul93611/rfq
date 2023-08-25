<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

$activeWorksheet;
$activeWorksheet->setCellValue('A1', '');
$activeWorksheet->setCellValue('B1', '');
$activeWorksheet->setCellValue('C1', '');
$activeWorksheet->setCellValue('D1', '');
$activeWorksheet->setCellValue('E1', '');
$activeWorksheet->setCellValue('F1', '');
$activeWorksheet->setCellValue('G1', '');
$activeWorksheet->mergeCells('H1:K1');
$activeWorksheet->getStyle('H1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('H1', 'QUOTE');
$activeWorksheet->mergeCells('L1:O1');
$activeWorksheet->getStyle('L1:O1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('L1', 'RE-QUOTE');
$activeWorksheet->getStyle('P1:R1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('P1', 'FULFILLMENT');

$activeWorksheet->setCellValue('A2', 'PROPOSAL #');
$activeWorksheet->setCellValue('B2', 'INVOICE DATE');
$activeWorksheet->setCellValue('C2', 'CONTRACT NUMBER');
$activeWorksheet->setCellValue('D2', 'CODE');
$activeWorksheet->setCellValue('E2', 'DESIGNATED USER');
$activeWorksheet->setCellValue('F2', 'STATE');
$activeWorksheet->setCellValue('G2', 'CLIENT');
$activeWorksheet->getStyle('H2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('H2', 'TOTAL COST');
$activeWorksheet->getStyle('I2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('I2', 'TOTAL PRICE');
$activeWorksheet->getStyle('J2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('J2', 'PROFIT');
$activeWorksheet->getStyle('K2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('K2', 'PROFIT(%)');
$activeWorksheet->getStyle('L2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('L2', 'TOTAL COST');
$activeWorksheet->getStyle('M2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('M2', 'TOTAL PRICE');
$activeWorksheet->getStyle('N2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('N2', 'PROFIT');
$activeWorksheet->getStyle('O2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('O2', 'PROFIT(%)');
$activeWorksheet->getStyle('P2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('P2', 'TOTAL COST');
$activeWorksheet->getStyle('Q2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('Q2', 'TOTAL PRICE');
$activeWorksheet->getStyle('R2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('R2', 'PROFIT');
$activeWorksheet->getStyle('S2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('S2', 'PROFIT(%)');
$activeWorksheet->setCellValue('T2', 'TYPE OF CONTRACT');
$activeWorksheet->setCellValue('U2', 'SALES COMMISSION');

Conexion::abrir_conexion();
ExcelRepository::salesCommissionReport(Conexion::obtener_conexion(), $_POST['type'], $_POST['quarter'], $_POST['month'], $_POST['year'], $activeWorksheet);
Conexion::cerrar_conexion();

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="SalesCommissionReport.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
