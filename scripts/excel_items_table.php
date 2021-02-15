<?php
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once 'vendor_excel/phpoffice/phpspreadsheet/src/Bootstrap.php';
$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);
    return;
}
Conexion::abrir_conexion();
$providers_name = RepositorioRfq::get_all_providers_name(Conexion::obtener_conexion(), $id_rfq);
$requote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$requote_providers_name = ReQuoteRepository::get_all_providers_name(Conexion::obtener_conexion(), $requote-> get_id());
$x = 'A';

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('E-logic.Inc')
    ->setLastModifiedBy('E-logic')
    ->setTitle('QuoteItemsTable')
    ->setSubject('QuoteItemsTable')
    ->setDescription('QuoteItemsTable')
    ->setKeywords('QuoteItemsTable')
    ->setCategory('QuoteItemsTable');

$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', '#');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', 'PROJECT SPECIFICATIONS');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', 'E-LOGIC PROPOSAL');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e8c92e');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', 'PART NUMBER');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('c2e34b');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', 'QUANTITY');$x++;
foreach ($providers_name as $i => $provider_name) {
  $spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4be3e3');
  $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', strtoupper($provider_name));
  $x++;
}
foreach ($requote_providers_name as $i => $requote_provider_name) {
  $spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('03befc');
  $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', strtoupper($requote_provider_name));
  $x++;
}
$spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('668fe8');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', 'PRICE FOR CLIENT');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('668fe8');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', 'TOTAL PRICE');$x++;

ExcelRepository::print_items(Conexion::obtener_conexion(), $spreadsheet, $providers_name, $requote_providers_name, $requote, $id_rfq);

Conexion::cerrar_conexion();

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="QuoteItemsTable.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
