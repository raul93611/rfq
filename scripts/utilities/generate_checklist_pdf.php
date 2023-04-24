<?php
include_once 'vendor/autoload.php';
$checkbox = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><rect width="20" height="20" fill="none" stroke="#000000" stroke-width="2"/></svg>';
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
  .blue{
    color: #004A97;
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
      <td style="vertical-align:top;">
        <h3>FILE DOCUMENT</h3><br>
        ';
  foreach (FILE_DOCUMENT as $key => $file_document) {
    $html .= $checkbox . ' ' . $file_document . '<br>';
  }
  $html .= '<br><h3>ACCOUNTING</h3><br>';
  foreach (ACCOUNTING_CHECKBOX as $key => $accounting) {
    $html .= $checkbox . ' ' . $accounting . '<br>';
  }
  $html .= '</td>
      <td style="width:70%;padding: 0;margin: 0;vertical-align:top;">
      <table border=0 width="100%">
        <tr>
          <td><b>Set Aside:</b></td>
          <td>' . $quote->getSetSide() . '</td>
        </tr>
        <tr>
          <td><b>Channel:</b></td>
          <td>' . $quote->obtener_canal() . '</td>
        </tr>
        <tr>
          <td><b>GSA:</b></td>
          <td>' . GSA[$quote->getGsa()] . '</td>
        </tr>
        <tr>
          <td><b>Contract number:</b></td>
          <td>' . $quote->obtener_contract_number() . '</td>
        </tr>
        <tr>
          <td><b>Sales Rep:</b></td>
          <td>' . $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos() . '</td>
        </tr>
        <tr>
          <td><b>RFQ/RFP Number:</b></td>
          <td>' . $quote->obtener_email_code() . '</td>
        </tr>
        <tr>
          <td><b>Award Date:</b></td>
          <td>' . $quote->obtener_fecha_award() . '</td>
        </tr>
        <tr>
          <td><b>POC:</b></td>
          <td>' . $quote->getPoc() . '</td>
        </tr>
        <tr>
          <td><b>CO:</b></td>
          <td>' . $quote->getCo() . '</td>
        </tr>
        <tr>
          <td><b>Client:</b></td>
          <td>' . $quote->obtener_client() . '</td>
        </tr>
        <tr>
          <td><b>Contract Amount:</b></td>
          <td>$ ' . $quote->obtener_quote_total_price() . '</td>
        </tr>
        <tr>
          <td><b>RFQ Amount:</b></td>
          <td>$ ' . $quote->obtener_total_price() . '</td>
        </tr>
        <tr>
          <td><b>RFP Amount:</b></td>
          <td>$ ' . $quote->getTotalQuoteServices() . '</td>
        </tr>
        <tr>
          <td><b>RFP Amount:</b></td>
          <td>$ ' . $quote->getTotalQuoteServices() . '</td>
        </tr>
        <tr>
          <td><b>Estimated Delivery Date:</b></td>
          <td>' . (!empty($quote->getEstimatedDeliveryDate()) ? RepositorioComment::mysql_date_to_english_format($quote->getEstimatedDeliveryDate()) : '') . '</td>
        </tr>
        <tr>
          <td><b>Client Payment Terms:</b></td>
          <td>' . CLIENT_PAYMENT_TERMS[$quote->getClientPaymentTerms()] . '</td>
        </tr>
        <tr>
          <td><b>Estimated Profit:</b></td>
          <td>$' . $quote->obtener_re_quote_profit() . ' / ' . number_format($quote->obtener_re_quote_profit_percentage(), 2) . ' %' . '</td>
        </tr>
        <tr>
          <td><b>Shipping Address:</b></td>
          <td>' . SHIPPING_ADDRESS[$quote->getShippingAddress()] . '</td>
        </tr>
        <tr>
          <td><b>City:</b></td>
          <td>' . $quote->obtener_city() . '</td>
        </tr>
        <tr>
          <td><b>Zip Code:</b></td>
          <td>' . $quote->obtener_zip_code() . '</td>
        </tr>
        <tr>
          <td><b>State:</b></td>
          <td>' . $quote->obtener_state() . '</td>
        </tr>
        <tr>
          <td><b>Ship to:</b></td>
          <td>' . $quote->obtener_ship_to() . '</td>
        </tr>
        <tr>
          <td><b>Special Requirements/Risk/Extra Comments:</b></td>
          <td>' . $quote->getSpecialRequirements() . '</td>
        </tr>
      </table>
      </td>
    </tr>
  </table>';
  $html .= '<br><br>';
  $html .= '<b class="blue">ACCEPTED BY:</b>';
  $html .= '<br><br>';
  $html .= '<b class="blue">DATE-SIGNATURE:</b>';
  $html .= '</body></html>';
  $mpdf->WriteHTML($html);
  $mpdf->Output($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $quote->obtener_id() . '/' . preg_replace('/[^a-z0-9-_\-\.]/i', '_', $quote->obtener_email_code()) . '(checklist)' . '.pdf', 'F');
  $mpdf->Output(preg_replace('/[^a-z0-9-_\-\.]/i', '_', $quote->obtener_email_code()) . '(checklist).pdf', 'I');
} catch (Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
