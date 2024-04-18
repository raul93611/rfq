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
$activeWorksheet->mergeCells('G1:I1');
$activeWorksheet->getStyle('G1:I1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('G1', 'QUOTE');
$activeWorksheet->mergeCells('J1:M1');
$activeWorksheet->getStyle('J1:N1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('J1', 'RE-QUOTE');
$activeWorksheet->getStyle('O1:S1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('dc3545');
$activeWorksheet->setCellValue('O1', 'FULFILLMENT');

$activeWorksheet->setCellValue('A2', 'PROPOSAL #');
$activeWorksheet->setCellValue('B2', 'INVOICE DATE');
$activeWorksheet->setCellValue('C2', 'CONTRACT NUMBER');
$activeWorksheet->setCellValue('D2', 'DESIGNATED USER');
$activeWorksheet->setCellValue('E2', 'STATE');
$activeWorksheet->setCellValue('F2', 'CLIENT');
$activeWorksheet->getStyle('G2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('G2', 'TOTAL COST');
$activeWorksheet->getStyle('H2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('H2', 'TOTAL PRICE');
$activeWorksheet->getStyle('I2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$activeWorksheet->setCellValue('I2', 'PROFIT');
$activeWorksheet->getStyle('J2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('J2', 'TOTAL COST');
$activeWorksheet->getStyle('K2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('K2', 'TOTAL PRICE');
$activeWorksheet->getStyle('L2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('L2', 'PROFIT RFQ');
$activeWorksheet->getStyle('M2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('M2', 'PROFIT RFP');
$activeWorksheet->getStyle('N2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$activeWorksheet->setCellValue('N2', 'PROFIT %');
$activeWorksheet->getStyle('O2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('dc3545');
$activeWorksheet->setCellValue('O2', 'TOTAL COST');
$activeWorksheet->getStyle('P2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('dc3545');
$activeWorksheet->setCellValue('P2', 'TOTAL PRICE');
$activeWorksheet->getStyle('Q2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('dc3545');
$activeWorksheet->setCellValue('Q2', 'PROFIT RFQ');
$activeWorksheet->getStyle('R2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('dc3545');
$activeWorksheet->setCellValue('R2', 'PROFIT RFP');
$activeWorksheet->getStyle('S2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('dc3545');
$activeWorksheet->setCellValue('S2', 'PROFIT %');
$activeWorksheet->setCellValue('T2', 'TYPE OF CONTRACT');
$activeWorksheet->setCellValue('U2', 'SALES COMMISSION');
$activeWorksheet->setCellValue('V2', 'SALES COMMISSION AMOUNT');

Conexion::abrir_conexion();
ExcelRepository::salesCommissionReport(
  Conexion::obtener_conexion(), 
  $_POST['type'], 
  $_POST['quarter'] ?? null, 
  $_POST['month'] ?? null, 
  $_POST['year'] ?? null, 
  $activeWorksheet
);
Conexion::cerrar_conexion();

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="SalesCommissionReport.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
