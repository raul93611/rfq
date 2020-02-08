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
$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('E-logic.Inc')
    ->setLastModifiedBy('E-logic')
    ->setTitle('QuoteReport')
    ->setSubject('QuoteReport')
    ->setDescription('QuoteReport')
    ->setKeywords('QuoteReport')
    ->setCategory('QuoteReport');

$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'PROPOSAL');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'DESIGNATED USER');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'CHANNEL');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', 'CODE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', 'TYPE OF BID');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', 'ISSUE DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', 'END DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', 'TOTAL COST($)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', 'TOTAL PRICE($)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', 'COMPLETED DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('K1', 'SUBMITTED DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('L1', 'AWARD DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('M1', 'PAYMENT TERMS');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('N1', 'TAXES(%)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('O1', 'PROFIT(%)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('P1', 'SHIPPING COST($)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('Q1', 'SHIPPING');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('R1', 'RFP PROJECT');

Conexion::abrir_conexion();
$date_from = RepositorioComment::english_format_to_mysql_date($_POST['date_from']);
$date_to = RepositorioComment::english_format_to_mysql_date($_POST['date_to']);
if($_POST['quote_type'] == 'submitted'){
  $quotes = RepositorioRfq::get_all_submitted_quotes_between_dates(Conexion::obtener_conexion(), $date_from, $date_to);
}else if($_POST['quote_type'] == 'award'){
  $quotes = RepositorioRfq::get_all_award_quotes_between_dates(Conexion::obtener_conexion(), $date_from, $date_to);
}
Conexion::cerrar_conexion();

$i=2;

foreach ($quotes as $quote) {
  Conexion::abrir_conexion();
  $usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $quote-> obtener_usuario_designado());
  Conexion::cerrar_conexion();
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $quote-> obtener_id());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $usuario_designado-> obtener_nombre_usuario());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $quote-> obtener_canal());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $quote-> obtener_email_code());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $quote-> obtener_type_of_bid());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $quote-> obtener_issue_date());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $quote-> obtener_end_date());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $quote-> obtener_total_cost());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $quote-> obtener_total_price());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, $quote-> obtener_fecha_completado());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $quote-> obtener_fecha_submitted());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, $quote-> obtener_fecha_award());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$i, $quote-> obtener_payment_terms());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$i, $quote-> obtener_taxes());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, $quote-> obtener_profit());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $quote-> obtener_shipping_cost());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$i, $quote-> obtener_shipping());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('R'.$i, $quote-> obtener_rfp());
  $i++;
}

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="QuoteReport.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
