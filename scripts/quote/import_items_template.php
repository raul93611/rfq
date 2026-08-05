<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Column order matches processCsv()/processExcel() in import_items.php by position:
// 0-9 are the existing item columns (unchanged, so already-filled sheets keep lining up),
// 10-19 are the 5 new Provider Name/Price pairs appended at the end.
$headers = [
  'Brand', 'Part Number', 'Description',
  'Proposal Brand', 'Proposal Part Number', 'Proposal Description',
  'Quantity', 'Comments', 'Website', 'Room',
  'Provider 1 Name', 'Provider 1 Price',
  'Provider 2 Name', 'Provider 2 Price',
  'Provider 3 Name', 'Provider 3 Price',
  'Provider 4 Name', 'Provider 4 Price',
  'Provider 5 Name', 'Provider 5 Price',
];

$spreadsheet = new Spreadsheet();
$spreadsheet->getActiveSheet()->fromArray($headers, null, 'A1');

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Item_Import_Template.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
