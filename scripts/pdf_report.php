<?php
include_once 'vendor/autoload.php';
list($nombres_usuario, $cotizaciones_completadas, $cotizaciones_completadas_pasadas, $cotizaciones_ganadas, $cotizaciones_ganadas_pasadas, $cotizaciones_sometidas, $cotizaciones_sometidas_pasadas, $cotizaciones_no_sometidas, $cotizaciones_no_sometidas_pasadas) = RepositorioUsuario::obtener_array_nombres_usuario_cotizaciones_completadas_ganadas_sometidas();
Conexion::abrir_conexion();
$cotizaciones_mes = RepositorioRfq::obtener_cotizaciones_ganadas_por_mes(Conexion::obtener_conexion());
$monto_cotizaciones_mes = RepositorioRfq::obtener_monto_cotizaciones_ganadas_por_mes(Conexion::obtener_conexion());
list($no_bid, $manufacturer_in_the_bid, $expired_due_date, $supplier_did_not_provide_a_quote, $others) = RepositorioRfq::obtener_comments(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
$hoy = date("F, Y");
try{
  $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
  $fontDirs = $defaultConfig['fontDir'];
  $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
  $fontData = $defaultFontConfig['fontdata'];
  $mpdf = new \Mpdf\Mpdf(['format' => 'Letter', 'margin_footer' => '8',
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
      <img style="width:350px;height:130px;" src="' . RUTA_IMG . '/logo_proposal.jpg">
      </td>
      <td align="right" valign="top">
        <span class="color letra_grande">REPORT</span>
      </td>
    </tr>
  </table>
  <div >
  </div>';

  $html .= '
  <h1 class="color">' . $hoy . '</h1>
  <h2 class="color">Quotes</h2>
  <table id="tabla" width="100%">
    <tr>
      <th>USERNAME</th>
      <th>COMPLETED</th>
      <th>AWARD</th>
    </tr>
  ';
  for ($i = 0; $i < count($nombres_usuario); $i++) {
    $html .= '<tr>';
    $html .= '
    <td>' . $nombres_usuario[$i] . '</td>
    <td style="text-align:right;">' . $cotizaciones_completadas[$i] . '</td>
    <td style="text-align:right;">' . $cotizaciones_ganadas[$i] . '</td>
    ';
    $html .= '</tr>';
  }
  $html .= '
  <tr>
    <td>TOTAL:</td>
    <td style="text-align:right;">' . array_sum($cotizaciones_completadas) . '</td>
    <td style="text-align:right;">' . array_sum($cotizaciones_ganadas) . '</td>
  </tr>
  ';
  $html .= '</table>';

  $no_bids = $no_bid + $manufacturer_in_the_bid + $expired_due_date + $supplier_did_not_provide_a_quote + $others;

  $html .= '
  <h2 class="color">No Bid - Current year</h2>
  <table id="tabla" width="100%">
    <tr>
      <th>COMMENTS</th>
      <th>QUANTITY</th>
    </tr>
    <tr>
      <td>No Bid</td>
      <td style="text-align: right;">' . $no_bid . '</td>
    </tr>
    <tr>
      <td>Manufacturer in the Bid</td>
      <td style="text-align: right;">' . $manufacturer_in_the_bid . '</td>
    </tr>
    <tr>
      <td>Expired due date</td>
      <td style="text-align: right;">' . $expired_due_date . '</td>
    </tr>
    <tr>
      <td>Supplier did not provide a quote</td>
      <td style="text-align: right;">' . $supplier_did_not_provide_a_quote . '</td>
    </tr>
    <tr>
      <td>Others</td>
      <td style="text-align: right;">' . $others . '</td>
    </tr>
    <tr>
      <td>TOTAL:</td>
      <td style="text-align: right;">' . $no_bids . '</td>
    </tr>
  </table>';

  $html .= '</body></html>';
  $mpdf->WriteHTML($html);
  $mpdf->Output($_SERVER['DOCUMENT_ROOT'] . '/rfq/reports/' . date('Y-m') . '.pdf', 'F');
  $mpdf->Output(date('Y-m') . '.pdf', 'I');
} catch (\Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
?>
