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
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'CARRIER');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'TRACKING #');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'DELIVERY DATE');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'DUE DATE');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'SIGNED BY');$x++;
$spreadsheet->getActiveSheet()->getStyle($x . $y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, 'COMMENT');$x++;

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
