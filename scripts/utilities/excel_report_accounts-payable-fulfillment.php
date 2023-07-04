<?php
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once 'vendor_excel/phpoffice/phpspreadsheet/src/Bootstrap.php';

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('E-logic.Inc')
    ->setLastModifiedBy('E-logic')
    ->setTitle('AccountsPayableFulfillmentReport')
    ->setSubject('AccountsPayableFulfillmentReport')
    ->setDescription('AccountsPayableFulfillmentReport')
    ->setKeywords('AccountsPayableFulfillmentReport')
    ->setCategory('AccountsPayableFulfillmentReport');

$spreadsheet->setActiveSheetIndex(0);

$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'PROPOSAL');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'PROVIDER');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'REAL COST');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', 'PAYMENT TERM');

Conexion::abrir_conexion();
ExcelRepository::accountsPayableFulfillmentReport(Conexion::obtener_conexion(), $_POST['type'], $_POST['quarter'], $_POST['month'], $_POST['year'], $spreadsheet);
Conexion::cerrar_conexion();

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="AccountsPayableFulfillmentReport.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
