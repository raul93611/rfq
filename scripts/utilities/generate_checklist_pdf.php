<?php
include_once 'vendor/autoload.php';
$check = '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 20 20"><path fill="#00b300" d="M7.87 15.116l-4.914-4.914 1.767-1.767 3.147 3.146 7.779-7.779 1.767 1.768z"/></svg>';
$times = '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 20 20"><path fill="#ff0000" d="M15.9 2.6l1.5 1.5L11.5 10l5.9 5.9-1.5 1.5L10 11.5l-5.9 5.9-1.5-1.5L8.5 10 2.6 4.1l1.5-1.5L10 8.5l5.9-5.9z"/></svg>';
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $quote->obtener_usuario_designado());
Conexion::cerrar_conexion();
$logo = 'logo_proposal.jpg';
try {
  $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
  $fontDirs = $defaultConfig['fontDir'];
  $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
  $fontData = $defaultFontConfig['fontdata'];
  $mpdf = new Mpdf\Mpdf([
    'format' => 'Letter', 'margin_footer' => '8',
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
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
        <span class="color letra_grande"> CHECKLIST</span>
        <br><br>
        <table id="tabla">
          <tr>
            <th>PROPOSAL #</th>
          </tr>
          <tr>
            <td style="text-align:center;">' . $quote->obtener_id() . '</td>
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
      <td>
        <h3>FILE DOCUMENT</h3><br>
        ';
  foreach (FILE_DOCUMENT as $key => $file_document) {
    $html .= (in_array($key, $quote->getFileDocument()) ? $check :  $times) . ' ' . $file_document . '<br>';
  }
  $html .= '<br><h3>ACCOUNTING</h3><br>';
  foreach (ACCOUNTING_CHECKBOX as $key => $accounting) {
    $html .= (in_array($key, $quote->getAccounting()) ? $check :  $times) . ' ' . $accounting . '<br>';
  }
  $html .= '</td>
      <td style="width:60%">
      <b>Set Aside:</b> ' . $quote->getSetSide() . '<br>' .
    '<b>Channel:</b> ' . $quote->obtener_canal() . '<br>' .
    '<b>GSA:</b> ' . $quote->getGsa() . '<br>' .
    '<b>Contract number:</b> ' . $quote->obtener_contract_number() . '<br>' .
    '<b>Sales Rep:</b> ' . $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos() . '<br>' .
    '<b>RFQ/RFP Number:</b> ' . $quote->obtener_email_code() . '<br>' .
    '<b>Award Date:</b> ' . $quote->obtener_fecha_award() . '<br>' .
    '<b>POC:</b> ' . $quote->getPoc() . '<br>' .
    '<b>CO:</b> ' . $quote->getCo() . '<br>' .
    '<b>Client:</b> ' . $quote->obtener_client() . '<br>' .
    '<b>Contract Amount:</b> $' . $quote->obtener_quote_total_price() . '<br>' .
    '<b>RFQ Amount:</b> $' . $quote->obtener_total_price() . '<br>' .
    '<b>RFP Amount:</b> $' . $quote->getTotalQuoteServices() . '<br>' .
    '<b>Estimated Delivery Date:</b> $' . $quote->getEstimatedDeliveryDate() . '<br>' .
    '<b>Payment Terms:</b> $' . $quote->obtener_payment_terms() . '<br>' .
    '<b>Estimated Profit:</b> $' . $quote->obtener_re_quote_profit() . ' / ' . number_format($quote->obtener_re_quote_profit_percentage(), 2) . ' %' . '<br>' .
    '<b>Shipping Address:</b> $' . $quote->getShippingAddress() . '<br>' .
    '<b>City:</b> $' . $quote->obtener_city() . '<br>' .
    '<b>Zip Code:</b> $' . $quote->obtener_zip_code() . '<br>' .
    '<b>State:</b> $' . $quote->obtener_state() . '<br>' .
    '<b>Ship to:</b> $' . $quote->obtener_ship_to() . '<br>' .
    '<b>Special Requirements/Risk/Extra Comments:</b> $' . $quote->getSpecialRequirements() . '<br>' .

    '</td>
    </tr>
  </table>';
  $html .= '</body></html>';
  $mpdf->WriteHTML($html);
  $mpdf->Output($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $quote->obtener_id() . '/' . preg_replace('/[^a-z0-9-_\-\.]/i', '_', $quote->obtener_email_code()) . '(checklist)' . '.pdf', 'F');
  $mpdf->Output(preg_replace('/[^a-z0-9-_\-\.]/i', '_', $quote->obtener_email_code()) . '(checklist).pdf', 'I');
} catch (Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
