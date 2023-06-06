<?php
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once 'vendor_excel/phpoffice/phpspreadsheet/src/Bootstrap.php';

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('E-logic.Inc')
    ->setLastModifiedBy('E-logic')
    ->setTitle('FulfillmentReport')
    ->setSubject('FulfillmentReport')
    ->setDescription('FulfillmentReport')
    ->setKeywords('FulfillmentReport')
    ->setCategory('FulfillmentReport');

$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', '');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', '');
$spreadsheet->getActiveSheet()->mergeCells('H1:K1');
$spreadsheet->getActiveSheet()->getStyle('H1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', 'QUOTE');
$spreadsheet->getActiveSheet()->mergeCells('L1:O1');
$spreadsheet->getActiveSheet()->getStyle('L1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('L1', 'RE-QUOTE');

$spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'FULFILLMENT DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', 'PROPOSAL #');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C2', 'DESIGNATED USER');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D2', 'CHANNEL');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E2', 'CODE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F2', 'TYPE OF BID');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G2', 'CONTRACT NUMBER');
$spreadsheet->getActiveSheet()->getStyle('H2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H2', 'TOTAL COST');
$spreadsheet->getActiveSheet()->getStyle('I2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I2', 'TOTAL PRICE');
$spreadsheet->getActiveSheet()->getStyle('J2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('J2', 'PROFIT');
$spreadsheet->getActiveSheet()->getStyle('K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('K2', 'PROFIT(%)');
$spreadsheet->getActiveSheet()->getStyle('L2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('L2', 'TOTAL COST');
$spreadsheet->getActiveSheet()->getStyle('M2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('M2', 'TOTAL PRICE');
$spreadsheet->getActiveSheet()->getStyle('N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('N2', 'PROFIT');
$spreadsheet->getActiveSheet()->getStyle('O2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0e716');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('O2', 'PROFIT(%)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('P2', 'TYPE');

Conexion::abrir_conexion();
ExcelRepository::fulfillmentReport(Conexion::obtener_conexion(), $_POST['type'], $_POST['quarter'], $_POST['month'], $_POST['year'], $spreadsheet);
Conexion::cerrar_conexion();

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="FulfillmentReport.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
