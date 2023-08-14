<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

Conexion::abrir_conexion();
$providers_name = RepositorioRfq::get_all_providers_name(Conexion::obtener_conexion(), $id_rfq);
$requote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$requote_providers_name = ReQuoteRepository::get_all_providers_name(Conexion::obtener_conexion(), $requote->get_id());

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

$x = 6;
$activeWorksheet->mergeCells(Coordinate::stringFromColumnIndex($x) . '1:' . Coordinate::stringFromColumnIndex($x - 1 + count($providers_name)) . '1');
$activeWorksheet->getStyle(Coordinate::stringFromColumnIndex($x) . '1:' . Coordinate::stringFromColumnIndex($x - 1 + count($providers_name)) . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4be3e3');
$activeWorksheet->setCellValue(Coordinate::stringFromColumnIndex($x) . '1', 'QUOTE');

$x = $x + count($providers_name);
$activeWorksheet->mergeCells(Coordinate::stringFromColumnIndex($x) . '1:' . Coordinate::stringFromColumnIndex($x - 1 + count($requote_providers_name)) . '1');
$activeWorksheet->getStyle(Coordinate::stringFromColumnIndex($x) . '1:' . Coordinate::stringFromColumnIndex($x - 1 + count($requote_providers_name)) . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('03befc');
$activeWorksheet->setCellValue(Coordinate::stringFromColumnIndex($x) . '1', 'RE-QUOTE');

$x = 'A';
$activeWorksheet;
$activeWorksheet->getStyle($x . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . '2', '#');
$x++;
$activeWorksheet->getStyle($x . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . '2', 'PROJECT SPECIFICATIONS');
$x++;
$activeWorksheet->getStyle($x . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ed9191');
$activeWorksheet->setCellValue($x . '2', 'E-LOGIC PROPOSAL');
$x++;
$activeWorksheet->getStyle($x . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('e8c92e');
$activeWorksheet->setCellValue($x . '2', 'PART NUMBER');
$x++;
$activeWorksheet->getStyle($x . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('c2e34b');
$activeWorksheet->setCellValue($x . '2', 'QUANTITY');
$x++;
foreach ($providers_name as $i => $provider_name) {
  $activeWorksheet->getStyle($x . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('4be3e3');
  $activeWorksheet->setCellValue($x . '2', strtoupper($provider_name));
  $x++;
}
foreach ($requote_providers_name as $i => $requote_provider_name) {
  $activeWorksheet->getStyle($x . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('03befc');
  $activeWorksheet->setCellValue($x . '2', strtoupper($requote_provider_name));
  $x++;
}
$activeWorksheet->getStyle($x . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('668fe8');
$activeWorksheet->setCellValue($x . '2', 'PRICE FOR CLIENT');
$x++;
$activeWorksheet->getStyle($x . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('668fe8');
$activeWorksheet->setCellValue($x . '2', 'TOTAL PRICE');
$x++;

ExcelRepository::print_items(Conexion::obtener_conexion(), $activeWorksheet, $providers_name, $requote_providers_name, $requote, $id_rfq);

Conexion::cerrar_conexion();

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ExcelItemsTable.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
