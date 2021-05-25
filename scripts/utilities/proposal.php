<?php
include_once 'vendor/autoload.php';
Database::open_connection();
$quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
$assigned_user = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote->get_assigned_user());
$items = ItemRepository::get_all_by_id_quote(Database::get_connection(), $id_quote);
if($quote-> get_type_of_bid() == 'Services'){
  $services = ServiceRepository::get_services(Database::get_connection(), $id_quote);
  $total_service = ServiceRepository::get_total(Database::get_connection(), $id_quote);
}else{
  $total_service = 0;
}
Database::close_connection();
$completed_date = CommentRepository::mysql_date_to_english_format($quote->get_completed_date());
$expiration_date = CommentRepository::mysql_date_to_english_format($quote->get_expiration_date());
try{
  $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
  $fontDirs = $defaultConfig['fontDir'];
  $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
  $fontData = $defaultFontConfig['fontdata'];
  $mpdf = new Mpdf\Mpdf(['format' => 'Letter', 'margin_footer' => '8',
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
  #tabla th,#tabla td {
    border: 1px solid #DEE8F2;

    padding-left: 10px;
    padding-right: 10px;
    padding-top: 5px;
    padding-bottom: 5px;
    font-size: 9pt;
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
    font-size: 25pt;
  }
  </style>
  </head>';
  $html .= '<body>
  <table border=0 width="100%">
    <tr>
      <td width="400">
      <img style="width:350px;height:130px;" src="img/logo_proposal.jpg">
      </td>
      <td align="right">
        <span class="color letra_grande">PROPOSAL</span>
        <br><br>
        <table id="tabla">
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
  </div>';
  $html .= '
  <br>
  <table id="tabla" style="width:100%">
    <tr>
      <th style="width:50%">ADDRESS</th>
      <th style="width:50%">SHIP TO</th>
    </tr>
    <tr>
      <td>' . nl2br($quote->get_address()) . '</td>
      <td>' . nl2br($quote->get_ship_to()) . '</td>
    </tr>
  </table>
  <br>
  <table id="tabla" style="width:100%">
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
  </table><br>';
      if ($encabezado) {
          $html .= '<table id="tabla" style="width:100%">
          <tr>
            <th class="quantity">#</th>
            <th>DESCRIPTION</th>
            <th class="quantity">QTY</th>
            <th>UNIT PRICE</th>
            <th class="total_ancho">TOTAL</th>
          </tr>
              <tr>
        <td></td>
      <td>OPEN MARKET PRICING PROPOSAL<br><br>

  E-Logic is an SBA 8(a) and HUBZONE Certified SB<br>
  SBA 8(a) Case Number: 307867<br>
  SBA 8(a) Entrance Date: 09/30/2016<br>
  SBA 8(a) Exit Date: 09/30/2025<br><br>

  As authorized by FAR 19.8, Federal agencies may issue sole source contracts to 8(a) firms up to $4 million. <br><br>

  The agency CO should send "Offer Letter" and PWS to dcofferletters@sba.gov. The SBA processes the Offer Letter and returns it to the agency CO within 5 business days for contract processing.
  </td>
  <td></td>
  <td></td>
  <td></td>
    </tr>';
      } else {
          $html .= '<table id="tabla" style="width:100%">
          <tr>
            <th class="quantity">#</th>
            <th>DESCRIPTION</th>
            <th class="quantity">QTY</th>
            <th>UNIT PRICE</th>
            <th class="total_ancho">TOTAL</th>
          </tr>';
      }
      $a = 1;
      $limit = 400;
      if(count($items)){
        foreach ($items as $i => $item) {
          $html .= ProposalRepository::print_item($item, $limit, $a);
          $a++;
        }
      }
      if(isset($services)){
        foreach ($services as $key => $service) {
          $html .= ProposalRepository::print_service($service, $a);
          $a++;
        }
      }

      $html .= '
      <tr>
        <td style="border:none;"></td>
        <td colspan="3" style="font-size:10pt;">' . nl2br($quote->get_shipping()) .'</td>
        <td style="text-align:right;">$ ' . number_format($quote->get_shipping_cost(), 2) .'</td>
      </tr>
      <tr>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
      <td style="font-size:12pt;">TOTAL:</td>

      <td style="font-size:12pt;text-align:right;">$ ' . number_format($quote->get_total_price() + $total_service, 2) . '</td>
    </tr>';
      $html .= '</table>';
  if ($quote->get_payment_terms() == 'Net 30') {
    $html .= '<br><div class="color letra_chiquita"><b>PAYMENT TERMS</b><br><b>NET TERMS: </b>30 Days<br><b>CREDIT CARD PAYMENT: </b>Please add an additional 3% to process credit card payments.</div>';
  }
  $html .= '</body></html>';
  $mpdf->SetHTMLFooter('
  <div class="color letra_chiquita" style="text-align:center;">
  EIN: 51-0629765, DUNS: 786-965876, CAGE:4QTF4<br>SBA 8(a) and HUBZone certified
  </div>
  ');
  $mpdf->showImageErrors = true;
  $mpdf->WriteHTML($html);
  $mpdf->Output($_SERVER['DOCUMENT_ROOT'] . '/rfq/documents/' . $quote->get_id() . '/' . preg_replace('/[^a-z0-9-_\-\.]/i','_', $quote-> get_email_code()) . '(proposal)' . '.pdf', 'F');
  $mpdf->Output(preg_replace('/[^a-z0-9-_\-\.]/i','_', $quote-> get_email_code()) . '.pdf', 'I');
} catch (Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
?>
