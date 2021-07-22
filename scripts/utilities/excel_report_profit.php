<?php
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once 'vendor_excel/phpoffice/phpspreadsheet/src/Bootstrap.php';

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('E-logic.Inc')
    ->setLastModifiedBy('E-logic')
    ->setTitle('ProfitReport')
    ->setSubject('ProfitReport')
    ->setDescription('ProfitReport')
    ->setKeywords('ProfitReport')
    ->setCategory('ProfitReport');

$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', '');
$spreadsheet->getActiveSheet()->mergeCells('C1:E1');
$spreadsheet->getActiveSheet()->getStyle('C1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'QUOTE');
$spreadsheet->getActiveSheet()->mergeCells('F1:H1');
$spreadsheet->getActiveSheet()->getStyle('F1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', 'RE-QUOTE');
$spreadsheet->getActiveSheet()->mergeCells('I1:K1');
$spreadsheet->getActiveSheet()->getStyle('I1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('37A745');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', 'FULFILLMENT');

$spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'INVOICE DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', 'PROPOSAL #');
$spreadsheet->getActiveSheet()->getStyle('C2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C2', 'TOTAL COST');
$spreadsheet->getActiveSheet()->getStyle('D2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D2', 'TOTAL PRICE');
$spreadsheet->getActiveSheet()->getStyle('E2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E2', 'PROFIT');
$spreadsheet->getActiveSheet()->getStyle('F2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F2', 'TOTAL COST');
$spreadsheet->getActiveSheet()->getStyle('G2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G2', 'TOTAL PRICE');
$spreadsheet->getActiveSheet()->getStyle('H2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H2', 'PROFIT');
$spreadsheet->getActiveSheet()->getStyle('I2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('37A745');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I2', 'TOTAL COST');
$spreadsheet->getActiveSheet()->getStyle('J2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('37A745');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('J2', 'TOTAL PRICE');
$spreadsheet->getActiveSheet()->getStyle('K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('37A745');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('K2', 'PROFIT');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('L2', 'TYPE');

Conexion::abrir_conexion();
ExcelRepository::profit_report(Conexion::obtener_conexion(), $_POST['month'], $_POST['year'], $spreadsheet);
Conexion::cerrar_conexion();

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ProfitReport.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
