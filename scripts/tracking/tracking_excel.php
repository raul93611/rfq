<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$x = 'A';

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

$activeWorksheet->getStyle($x . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . '1', 'PROPOSAL #');
$x++;
$activeWorksheet->getStyle($x . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . '1', 'CONTRACT NUMBER');

$x = 'A';
$activeWorksheet->setCellValue($x . '2', $quote->obtener_id());
$x++;
$activeWorksheet->setCellValue($x . '2', $quote->obtener_contract_number());

$x = 'A';
$y = 4;
$activeWorksheet;
$activeWorksheet->getStyle($x . $y)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . $y, '#');
$x++;
$activeWorksheet->getStyle($x . $y)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . $y, 'PROJECT ESPC');
$x++;
$activeWorksheet->getStyle($x . $y)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . $y, 'QTY(ORDERED)');
$x++;
$activeWorksheet->getStyle($x . $y)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . $y, 'QTY(SHIPPED)');
$x++;
$activeWorksheet->getStyle($x . $y)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . $y, 'CARRIER');
$x++;
$activeWorksheet->getStyle($x . $y)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . $y, 'TRACKING #');
$x++;
$activeWorksheet->getStyle($x . $y)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . $y, 'DELIVERY DATE');
$x++;
$activeWorksheet->getStyle($x . $y)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . $y, 'DUE DATE');
$x++;
$activeWorksheet->getStyle($x . $y)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . $y, 'SIGNED BY');
$x++;
$activeWorksheet->getStyle($x . $y)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . $y, 'COMMENT');
$x++;

ExcelRepository::print_tracking(Conexion::obtener_conexion(), $activeWorksheet, $quote, $re_quote);
Conexion::cerrar_conexion();

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="TrackingProposal' . $quote->obtener_id() . '.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
