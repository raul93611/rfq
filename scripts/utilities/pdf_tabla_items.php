<?php
include_once 'vendor/autoload.php';
Database::open_connection();
$quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
$assigned_user = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote->get_assigned_user());
$items = ItemRepository::get_all_by_id_quote(Database::get_connection(), $id_quote);
Database::close_connection();
$completed_date_parts = explode('-', $quote->get_completed_date());
$completed_date = $completed_date_parts[1] . '/' . $completed_date_parts[2] . '/' . $completed_date_parts[0];
$partes_expiration_date = explode('-', $quote->get_expiration_date());
$expiration_date = $partes_expiration_date[1] . '/' . $partes_expiration_date[2] . '/' . $partes_expiration_date[0];
try{
  $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
  $fontDirs = $defaultConfig['fontDir'];
  $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
  $fontData = $defaultFontConfig['fontdata'];
  $mpdf = new Mpdf\Mpdf(['format' => 'Letter-L', 'margin_footer' => '8',
  'fontDir' => array_merge($fontDirs, [
          SERVER . '/vendor/mpdf/mpdf/ttfonts',
      ]),
      'fontdata' => $fontData + [
          'roboto' => [
              'R' => 'Roboto-Regular.ttf',
              'I' => 'Roboto-Italic.ttf',
          ]
      ],
      'default_font' => 'roboto'
  ]);
  $html = '<!DOCTYPE html>
  <html>
  <head>
  <style>
  body{
    font-family: roboto;
  }
  th{
    color: #004A97;
    background-color: #DEE8F2;
  }
  .tabla th,.tabla td {
    border: 1px solid #DEE8F2;

    padding-left: 10px;
    padding-right: 10px;
    padding-top: 5px;
    padding-bottom: 5px;
    font-size: 7pt;
  }
  table, th, td{
    border-collapse: collapse;
  }
  td{
    color: #3B3B3B;
  }

  .quantity{
    width: 20px;
  }

  .total_ancho{
    width: 130px;
  }

  .letra_chiquita{
    font-size: 8pt;
  }

  .color{
    color: #004A97;
  }
  .letra_grande{
    font-size: 15pt;
  }
  </style>
  </head>';
  $html .= '<body>
  <table border=0 width="100%">
    <tr>
      <td>
        <table class="tabla" style="width:100%">
          <tr>
            <th>SHIP VIA</th>
            <th>CONTRACT NUMBER</th>
            <th>SALES REP</th>
            <th>E-MAIL</th>
            <th>PAYMENT TERMS</th>
          </tr>
          <tr>
            <td style="text-align:center;">' . $quote->get_ship_via() . '</td>
            <td style="text-align:center;">' . $quote->get_email_code() . '</td>
            <td style="text-align:center;">' . $assigned_user->obtener_nombres() . ' ' . $assigned_user->obtener_apellidos() . '</td>
                <td style="text-align:center;">' . $assigned_user->obtener_email() . '</td>
            <td style="text-align:center;">' . $quote->get_payment_terms() . '</td>
          </tr>
        </table>
      </td>
      <td align="right">
        <table class="tabla">
          <tr>
            <th>PROPOSAL #</th>
            <th>DATE</th>
            <th>EXPIRATION DATE</th>
          </tr>
          <tr>
            <td style="text-align:center;">' . $quote->get_id() . '</td>
            <td style="text-align:center;">' . $completed_date . '</td>
            <td style="text-align:center;">' . $expiration_date . '</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <div >
  </div>
  <div class="color">
    <b>Taxes:</b> '  . $quote-> get_taxes() . '%<br>
    <b>Profit:</b> ' . $quote-> get_profit() . '%<br>
    <b>Additional general: </b> $ ' . $quote-> get_additional() . '
  </div>
  <br>';
  if (count($items)) {
      $html .= '<table class="tabla" style="width:100%;">
      <tr>
        <th class="quantity">#</th>
        <th>PROJECT ESPC.</th>
        <th>E-LOGIC PROP.</th>
        <th class="quantity">QTY</th>
        <th>PROVIDER</th>
        <th>ADDITIONAL</th>
        <th>BEST UNIT COST</th>
        <th>TOTAL COST</th>
        <th>PRICE FOR CLIENT</th>
        <th class="total_ancho">TOTAL PRICE</th>
      </tr>';
      $a = 1;
      if($quote-> get_payment_terms() == 'Net 30/CC'){
        $payment_terms = 1.0299;
      }else{
        $payment_terms = 1;
      }
      foreach ($items as $key => $item) {
        $html .= ProposalRepository::print_item_pdf($quote, $item, $a, $payment_terms);
        $a++;
      }
      $html .= '
      <tr>
        <td style="border:none;"></td>
        <td colspan="8" style="font-size:10pt;">' . nl2br($quote->get_shipping()) .'</td>
        <td style="text-align:right;">$ ' . number_format($quote->get_shipping_cost(), 2) .'</td>
      </tr>
      <tr>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="font-size:12pt;">TOTAL:</td>
          <td>$ ' . number_format($quote-> get_total_cost(), 2) . '</td>
          <td></td>

      <td style="font-size:12pt;text-align:right;">$ ' . number_format($quote->get_total_price(), 2) . '</td>
    </tr>';
      $html .= '</table>';
  }
  if ($quote->get_payment_terms() == 'Net 30') {
    $html .= '<br><div class="color letra_chiquita"><b>PAYMENT TERMS</b><br><b>NET TERMS: </b>30 Days<br><b>CREDIT CARD PAYMENT: </b>Please add an additional 2.1% to process credit card payments.</div>';
  }
  $html .= '</body></html>';
  $mpdf->SetHTMLFooter('
  <div class="color letra_chiquita" style="text-align:center;">
  EIN: 51-0629765, DUNS: 786-965876, CAGE:4QTF4<br>SBA 8(a) and HUBZone certified
  </div>
  ');
  $mpdf->WriteHTML($html);
  $mpdf->Output(preg_replace('/[^a-z0-9-_\-\.]/i','_', $quote-> get_email_code()) . '(items_table).pdf', 'I');
} catch (Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
?>
