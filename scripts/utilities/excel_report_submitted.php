<?php
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once 'vendor_excel/phpoffice/phpspreadsheet/src/Bootstrap.php';

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('E-logic.Inc')
    ->setLastModifiedBy('E-logic')
    ->setTitle('SubmittedReport')
    ->setSubject('SubmittedReport')
    ->setDescription('SubmittedReport')
    ->setKeywords('SubmittedReport')
    ->setCategory('SubmittedReport');

$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'SUBMITTED DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'PROPOSAL #');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'DESIGNATED USER');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', 'CHANNEL');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', 'TYPE OF BID');
$spreadsheet->getActiveSheet()->getStyle('F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', 'TOTAL COST');
$spreadsheet->getActiveSheet()->getStyle('G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', 'TOTAL PRICE');
$spreadsheet->getActiveSheet()->getStyle('H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', 'PROFIT');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', 'TYPE');

Conexion::abrir_conexion();
ExcelRepository::submitted_report(Conexion::obtener_conexion(), $_POST['month'], $_POST['year'], $spreadsheet);
Conexion::cerrar_conexion();

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="SubmittedReport.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
