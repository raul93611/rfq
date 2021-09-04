<?php
include_once 'vendor/autoload.php';
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
if($cotizacion-> obtener_type_of_bid() == 'Services'){
  $services = ServiceRepository::get_services(Conexion::obtener_conexion(), $id_rfq);
  $total_service = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
}else{
  $total_service = 0;
}
Conexion::cerrar_conexion();
$fecha_completado = RepositorioComment::mysql_date_to_english_format($cotizacion->obtener_fecha_completado());
$expiration_date = RepositorioComment::mysql_date_to_english_format($cotizacion->obtener_expiration_date());
$logo = $cotizacion-> obtener_canal() == 'Stars III' ? 'logoSinergy.png' : 'logo_proposal.jpg';
try{
  $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
  $fontDirs = $defaultConfig['fontDir'];
  $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
  $fontData = $defaultFontConfig['fontdata'];
  $mpdf = new Mpdf\Mpdf(['format' => 'Letter', 'margin_footer' => '8',
  'fontDir' => array_merge($fontDirs, [
          SERVIDOR . '/vendor/mpdf/mpdf/ttfonts',
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
      <img style="width:350px;height:130px;" src="img/' . $logo . '">
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
            <td style="text-align:center;">' . $cotizacion->obtener_id() . '</td>
            <td style="text-align:center;">' . $fecha_completado . '</td>
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
      <td>' . nl2br($cotizacion->obtener_address()) . '</td>
      <td>' . nl2br($cotizacion->obtener_ship_to()) . '</td>
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
      <td style="text-align:center;">' . $cotizacion->obtener_ship_via() . '</td>
      <td style="text-align:center;">' . $cotizacion->obtener_email_code() . '</td>
      <td style="text-align:center;">' . $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos() . '</td>
          <td style="text-align:center;">' . $usuario_designado->obtener_email() . '</td>
      <td style="text-align:center;">' . $cotizacion->obtener_payment_terms() . '</td>
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

    E-Logic is an SBA 8(a) and HUBZONE Certified SB<br><br>
    SBA 8(a) Case Number: C0069X<br><br>
    SBA 8(a) Entrance Date: 09/30/2016<br><br>
    SBA 8(a) Exit Date: 09/30/2026
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
        <td style="border-bottom:0;border-left:0;"></td>
        <td colspan="3" style="font-size:10pt;">' . nl2br($cotizacion->obtener_shipping()) .'</td>
        <td style="text-align:right;">$ ' . number_format($cotizacion->obtener_shipping_cost(), 2) .'</td>
      </tr>
      <tr>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
      <td style="font-size:12pt;">TOTAL:</td>

      <td style="font-size:12pt;text-align:right;">$ ' . number_format($cotizacion->obtener_total_price() + $total_service, 2) . '</td>
    </tr>';
      $html .= '</table>';
  if ($cotizacion->obtener_payment_terms() == 'Net 30') {
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
  $mpdf->Output($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion->obtener_id() . '/' . preg_replace('/[^a-z0-9-_\-\.]/i','_', $cotizacion-> obtener_email_code()) . '(proposal)' . '.pdf', 'F');
  $mpdf->Output(preg_replace('/[^a-z0-9-_\-\.]/i','_', $cotizacion-> obtener_email_code()) . '.pdf', 'I');
} catch (Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
?>
