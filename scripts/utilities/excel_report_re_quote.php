<?php
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once 'vendor_excel/phpoffice/phpspreadsheet/src/Bootstrap.php';

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('E-logic.Inc')
    ->setLastModifiedBy('E-logic')
    ->setTitle('ReQuoteReport')
    ->setSubject('ReQuoteReport')
    ->setDescription('ReQuoteReport')
    ->setKeywords('ReQuoteReport')
    ->setCategory('ReQuoteReport');

$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', '');
$spreadsheet->getActiveSheet()->mergeCells('I1:L1');
$spreadsheet->getActiveSheet()->getStyle('I1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', 'QUOTE');
$spreadsheet->getActiveSheet()->mergeCells('M1:P1');
$spreadsheet->getActiveSheet()->getStyle('M1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('M1', 'RE-QUOTE');

$spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'FULFILLMENT DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', 'PROPOSAL #');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C2', 'DESIGNATED USER');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D2', 'CHANNEL');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E2', 'CODE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F2', 'TYPE OF BID');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G2', 'AWARD DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H2', 'CONTRACT NUMBER');
$spreadsheet->getActiveSheet()->getStyle('I2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I2', 'TOTAL COST');
$spreadsheet->getActiveSheet()->getStyle('J2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('J2', 'TOTAL PRICE');
$spreadsheet->getActiveSheet()->getStyle('K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('K2', 'PROFIT');
$spreadsheet->getActiveSheet()->getStyle('L2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('L2', 'PROFIT(%)');
$spreadsheet->getActiveSheet()->getStyle('M2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('M2', 'TOTAL COST');
$spreadsheet->getActiveSheet()->getStyle('N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('N2', 'TOTAL PRICE');
$spreadsheet->getActiveSheet()->getStyle('O2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('O2', 'PROFIT');
$spreadsheet->getActiveSheet()->getStyle('P2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('P2', 'PROFIT(%)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('Q2', 'TYPE');

Conexion::abrir_conexion();
ExcelRepository::re_quote_report(Conexion::obtener_conexion(), $_POST['month'], $_POST['year'], $spreadsheet);
Conexion::cerrar_conexion();

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReQuoteReport.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
