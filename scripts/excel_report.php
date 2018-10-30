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
    ->setTitle('SubmittedQuoteReport')
    ->setSubject('SubmittedQuoteReport')
    ->setDescription('SubmittedQuoteReport')
    ->setKeywords('SubmittedQuoteReport')
    ->setCategory('SubmittedQuoteReport');

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
$spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', 'COMMENTS');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('K1', 'AWARD');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('L1', 'COMPLETED DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('M1', 'SUBMITTED DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('N1', 'AWARD DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('O1', 'PAYMENT TERMS');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('P1', 'ADDRESS');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('Q1', 'SHIP_TO');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('R1', 'EXPIRATION DATE');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('S1', 'SHIP_VIA');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('T1', 'TAXES(%)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('U1', 'PROFIT(%)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('V1', 'ADDITIONAL GENERAL($)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('W1', 'SHIPPING COST($)');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('X1', 'SHIPPING');

Conexion::abrir_conexion();
$todos_sometidos = RepositorioRfq::obtener_todos_quotes_sometidos(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
$i=2;


foreach ($todos_sometidos as $quote_sometido) {
  Conexion::abrir_conexion();
  $usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $quote_sometido-> obtener_usuario_designado());
  Conexion::cerrar_conexion();
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $quote_sometido-> obtener_id());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $usuario_designado-> obtener_nombre_usuario());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $quote_sometido-> obtener_canal());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $quote_sometido-> obtener_email_code());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $quote_sometido-> obtener_type_of_bid());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $quote_sometido-> obtener_issue_date());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $quote_sometido-> obtener_end_date());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $quote_sometido-> obtener_total_cost());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $quote_sometido-> obtener_total_price());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, $quote_sometido-> obtener_comments());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $quote_sometido-> obtener_award());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, $quote_sometido-> obtener_fecha_completado());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$i, $quote_sometido-> obtener_fecha_submitted());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$i, $quote_sometido-> obtener_fecha_award());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, $quote_sometido-> obtener_payment_terms());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $quote_sometido-> obtener_address());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$i, $quote_sometido-> obtener_ship_to());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('R'.$i, $quote_sometido-> obtener_expiration_date());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('S'.$i, $quote_sometido-> obtener_ship_via());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('T'.$i, $quote_sometido-> obtener_taxes());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('U'.$i, $quote_sometido-> obtener_profit());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('V'.$i, $quote_sometido-> obtener_additional());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('W'.$i, $quote_sometido-> obtener_shipping_cost());
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('X'.$i, $quote_sometido-> obtener_shipping());
  $i++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="SubmittedQuoteReport.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
