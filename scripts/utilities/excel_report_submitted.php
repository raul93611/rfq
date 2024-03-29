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
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'CODE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', 'DESIGNATED USER');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', 'CHANNEL');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', 'TYPE OF BID');
$spreadsheet->getActiveSheet()->getStyle('G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', 'TOTAL COST');
$spreadsheet->getActiveSheet()->getStyle('H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', 'TOTAL PRICE');
$spreadsheet->getActiveSheet()->getStyle('I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', 'PROFIT');
$spreadsheet->getActiveSheet()->getStyle('J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('197bff');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', 'PROFIT (%)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('K1', 'TYPE');

Conexion::abrir_conexion();
ExcelRepository::submittedReport(Conexion::obtener_conexion(), $_POST['type'], $_POST['quarter'], $_POST['month'], $_POST['year'], $spreadsheet);
Conexion::cerrar_conexion();

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="SubmittedReport.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
