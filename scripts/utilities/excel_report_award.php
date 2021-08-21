<?php
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once 'vendor_excel/phpoffice/phpspreadsheet/src/Bootstrap.php';

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('E-logic.Inc')
    ->setLastModifiedBy('E-logic')
    ->setTitle('AwardReport')
    ->setSubject('AwardReport')
    ->setDescription('AwardReport')
    ->setKeywords('AwardReport')
    ->setCategory('AwardReport');

$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'AWARD DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'PROPOSAL #');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'CONTRACT NUMBER');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', 'CODE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', 'DESIGNATED USER');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', 'CHANNEL');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', 'TYPE OF BID');
$spreadsheet->getActiveSheet()->getStyle('H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', 'TOTAL COST');
$spreadsheet->getActiveSheet()->getStyle('I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', 'TOTAL PRICE');
$spreadsheet->getActiveSheet()->getStyle('J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', 'PROFIT');
$spreadsheet->getActiveSheet()->getStyle('K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('K1', 'PROFIT (%)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('L1', 'TYPE');

Conexion::abrir_conexion();
ExcelRepository::award_report(Conexion::obtener_conexion(), $_POST['month'], $_POST['year'], $spreadsheet);
Conexion::cerrar_conexion();

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="AwardReport.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
