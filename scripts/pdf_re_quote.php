<?php
include_once 'vendor/autoload.php';
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote(Conexion::obtener_conexion(), $re_quote-> get_id());
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
Conexion::cerrar_conexion();
$partes_fecha_completado = explode('-', $cotizacion->obtener_fecha_completado());
$fecha_completado = $partes_fecha_completado[1] . '/' . $partes_fecha_completado[2] . '/' . $partes_fecha_completado[0];
$partes_expiration_date = explode('-', $cotizacion->obtener_expiration_date());
$expiration_date = $partes_expiration_date[1] . '/' . $partes_expiration_date[2] . '/' . $partes_expiration_date[0];
try{
  $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
  $fontDirs = $defaultConfig['fontDir'];
  $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
  $fontData = $defaultFontConfig['fontdata'];
  $mpdf = new \Mpdf\Mpdf(['format' => 'Letter-L', 'margin_footer' => '8',
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
  <h2>RE QUOTE</h2>
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
            <td style="text-align:center;">' . $cotizacion->obtener_ship_via() . '</td>
            <td style="text-align:center;">' . $cotizacion->obtener_email_code() . '</td>
            <td style="text-align:center;">' . $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos() . '</td>
                <td style="text-align:center;">' . $usuario_designado->obtener_email() . '</td>
            <td style="text-align:center;">' . $re_quote-> get_payment_terms() . '</td>
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
            <td style="text-align:center;">' . $cotizacion->obtener_id() . '</td>
            <td style="text-align:center;">' . $fecha_completado . '</td>
            <td style="text-align:center;">' . $expiration_date . '</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <div >
  </div>
  <br>';
  if (count($re_quote_items)) {
    $html .= '
    <table class="tabla" style="width:100%;">
    <tr>
    <th class="quantity">#</th>
    <th>PROJECT ESPC.</th>
    <th>E-LOGIC PROP.</th>
    <th class="quantity">QTY</th>
    <th>PROVIDER</th>
    <th>BEST UNIT COST</th>
    <th>TOTAL COST</th>
    <th>PRICE FOR CLIENT</th>
    <th>TOTAL PRICE</th>
    </tr>
    ';
    $a = 1;
    foreach ($re_quote_items as $key => $re_quote_item) {
      $html .= ProposalRepository::print_item_pdf_re_quote($re_quote_item, $items, $a, $key);
      $a++;
    }
    $html .= '
    <tr>
    <td style="border:none;"></td>
    <td colspan="5" style="font-size:10pt;">' . nl2br($re_quote-> get_shipping()) .'</td>
    <td style="text-align:right;">$ ' . number_format($re_quote-> get_shipping_cost(), 2) . '</td>
    <td></td>
    <td>$ ' . number_format($cotizacion-> obtener_shipping_cost(), 2) . '</td>
    </tr>
    <tr>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="font-size:12pt;">TOTAL:</td>
    <td>$ ' . number_format($re_quote-> get_total_cost(), 2) . '</td>
    <td></td>
    <td style="font-size:12pt;text-align:right;">$ ' . number_format($cotizacion-> obtener_total_price(), 2) . '</td>
    </tr>
    <tr>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="font-size:12pt;">PROFIT:</td>
    <td style="font-size:12pt;text-align:right;">$ ' . number_format($cotizacion-> obtener_total_price() - $re_quote-> get_total_cost(), 2) . '</td>
    </tr>
    <tr>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="border:none;"></td>
    <td style="font-size:12pt;text-align:right;">' . number_format((($cotizacion-> obtener_total_price() - $re_quote-> get_total_cost())/$re_quote-> get_total_cost())*100, 2) . ' %</td>
    </tr>
    </table>
    ';
    if ($cotizacion->obtener_payment_terms() == 'Net 30') {
      $html .= '<br><div class="color letra_chiquita"><b>PAYMENT TERMS</b><br><b>NET TERMS: </b>30 Days<br><b>CREDIT CARD PAYMENT: </b>Please add an additional 2.1% to process credit card payments.</div>';
    }
    $html .= '</body></html>';
    $mpdf->SetHTMLFooter('
    <div class="color letra_chiquita" style="text-align:center;">
    EIN: 51-0629765, DUNS: 786-965876, CAGE:4QTF4<br>SBA 8(a) and HUBZone certified
    </div>
    ');
    $mpdf->WriteHTML($html);
    $mpdf->Output($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion->obtener_id() . '/' . preg_replace('/[^a-z0-9-_\-\.]/i','_', $cotizacion-> obtener_email_code()) . '(re_quote_items_table)' . '.pdf', 'F');
    $mpdf->Output(preg_replace('/[^a-z0-9-_\-\.]/i','_', $cotizacion-> obtener_email_code()) . '(re_quote_items_table).pdf', 'I');
  }
} catch (\Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
