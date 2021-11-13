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
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$x = 'A';

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('E-logic.Inc')
    ->setLastModifiedBy('E-logic')
    ->setTitle('TrackingExcel')
    ->setSubject('TrackingExcel')
    ->setDescription('TrackingExcel')
    ->setKeywords('TrackingExcel')
    ->setCategory('TrackingExcel');

$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', 'PROPOSAL #');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '1', 'CONTRACT NUMBER');

$x = 'A';
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '2', $quote-> obtener_id());$x++;
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . '2', $quote-> obtener_contract_number());

$x = 'A';
$y = 4;
$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, '#');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'PROJECT ESPC');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'QTY(ORDERED)');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'QTY(SHIPPED)');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'TRACKING #');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'DELIVERY DATE');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'SIGNED BY');$x++;

// $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
// $re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote(Conexion::obtener_conexion(), $re_quote-> get_id());
//
// $y = 5;
// $a = 1;
// foreach ($items as $key => $item) {
//   $x = 'A';
//   // $trackings = TrackingRepository::get_all_trackings_by_id_item($connection, $item-> obtener_id());
//   $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', '2222');$x++;
//   // $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, "Brand name: " . $re_quote_item-> get_brand() . "\n Part number: " . $re_quote_item-> get_part_number() . "\n Description: " . nl2br(wordwrap(mb_substr($re_quote_item-> get_description(), 0, 150), 70, "<br>", true)));
//   // $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
//   $y++;
// }

ExcelRepository::print_tracking(Conexion::obtener_conexion(), $spreadsheet, $quote, $re_quote);
Conexion::cerrar_conexion();

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="TrackingProposal' . $quote-> obtener_id() . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
