<?php
include_once 'vendor/autoload.php';
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
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
            <td style="text-align:center;">' . $cotizacion->obtener_ship_via() . '</td>
            <td style="text-align:center;">' . $cotizacion->obtener_email_code() . '</td>
            <td style="text-align:center;">' . $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos() . '</td>
                <td style="text-align:center;">' . $usuario_designado->obtener_email() . '</td>
            <td style="text-align:center;">' . $cotizacion->obtener_payment_terms() . '</td>
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
  <div class="color">
    <b>Taxes:</b> '  . $cotizacion-> obtener_taxes() . '%<br>
    <b>Profit:</b> ' . $cotizacion-> obtener_profit() . '%<br>
    <b>Additional general: </b> $ ' . $cotizacion-> obtener_additional() . '
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
      if($cotizacion-> obtener_payment_terms() == 'Net 30/CC'){
        $payment_terms = 1.0215;
      }else{
        $payment_terms = 1;
      }
      for ($i = 0; $i < count($items); $i++) {
          $item = $items[$i];
            $html .= '<tr>
                <td>' . $a . '</td>
                <td><b>Brand name:</b> ' . $item-> obtener_brand_project() . '<br><b>Part number:</b> ' . $item-> obtener_part_number_project() . '<br><b>Item description:</b> ' . nl2br(wordwrap(mb_substr($item-> obtener_description_project(), 0, 150), 70, '<br>', true)) . '</td>
                <td><b>Brand name:</b> ' . $item->obtener_brand() . '<br><b>Part number:</b> ' . $item->obtener_part_number() . '<br><b> Item description:</b><br> ' . nl2br(wordwrap(mb_substr($item->obtener_description(), 0, 150), 70, '<br>', true)) . '</td>
                <td style="text-align:right;">' . $item->obtener_quantity() . '</td>
                <td>';
            Conexion::abrir_conexion();
            $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
            $provider_menor = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $item-> obtener_provider_menor());
            Conexion::cerrar_conexion();
            if(count($providers)){
              foreach ($providers as $provider) {
                $html .= '<b>' . $provider-> obtener_provider() . ':</b><br>$ ' . number_format($provider-> obtener_price(), 2) . '<br>';
              }
            }
            $html .= '
                </td>
                <td>$ ' . number_format($item-> obtener_additional(), 2) . '</td>
                <td>$ ';
            $best_unit_price = $provider_menor-> obtener_price()*$payment_terms*(1+($cotizacion-> obtener_taxes()/100)) + $item-> obtener_additional() + $cotizacion-> obtener_additional();
                $html .= number_format($best_unit_price, 2);
                $html .= '</td>
                <td>$ ' . number_format(round($best_unit_price, 2) * $item-> obtener_quantity(), 2) . '</td>
                <td style="text-align:right;">$ ' . number_format($item->obtener_unit_price(), 2) . '</td>
                <td style="text-align:right;">$ ' . number_format($item->obtener_total_price(), 2) . '</td>
              </tr>';
            Conexion::abrir_conexion();
            $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
            Conexion::cerrar_conexion();
            for($j = 0; $j < count($subitems); $j++){
              $subitem = $subitems[$j];
              $html .= '
                <tr>
                  <td></td>
                  <td><b>Brand name:</b> ' . $subitem-> obtener_brand_project() . '<br><b>Part number:</b> ' . $subitem-> obtener_part_number_project() . '<br><b>Item description:</b><br> ' . nl2br(wordwrap(mb_substr($subitem->obtener_description_project(), 0, 150), 70, '<br>', true)) . '</td>}
                  <td><b>Brand name:</b> ' . $subitem->obtener_brand() . '<br><b>Part number:</b> ' . $item->obtener_part_number() . '<br><b> Item description:</b><br> ' . nl2br(wordwrap(mb_substr($item->obtener_description(), 0, 150), 70, '<br>', true)) . '</td>
                  <td style="text-align:right;">' . $subitem-> obtener_quantity() . '</td>
                  <td>';
                  Conexion::abrir_conexion();
                  $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
                  $provider_subitem_menor = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $subitem-> obtener_provider_menor());
                  Conexion::cerrar_conexion();
                  if(count($providers_subitem)){
                    foreach ($providers_subitem as $provider_subitem) {
                      $html .= '<b>' . $provider_subitem-> obtener_provider()  . ':</b><br>$ ' . number_format($provider_subitem-> obtener_price(), 2) . '<br>';
                    }
                  }
              $html .= '
                  </td>
                  <td>$ ' . number_format($subitem-> obtener_additional(), 2) . '</td>
                  <td>$ ';
              $best_unit_price = $provider_subitem_menor-> obtener_price()*$payment_terms*(1+($cotizacion-> obtener_taxes()/100)) + $subitem-> obtener_additional() + $cotizacion-> obtener_additional();
                  $html .= number_format($best_unit_price, 2);
                  $html .= '</td>
                  <td>$ ' . number_format(round($best_unit_price, 2) * $subitem-> obtener_quantity(), 2) . '</td>
                  <td style="text-align:right;">$ ' . number_format($subitem-> obtener_unit_price(), 2) . '</td>
                  <td style="text-align:right;">$ ' . number_format($subitem-> obtener_total_price(), 2) . '</td>
                </tr>
              ';
            }
            $a++;
      }
      $html .= '
      <tr>
        <td style="border:none;"></td>
        <td colspan="8" style="font-size:10pt;">' . nl2br($cotizacion->obtener_shipping()) .'</td>
        <td style="text-align:right;">$ ' . number_format($cotizacion->obtener_shipping_cost(), 2) .'</td>
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
      <td style="font-size:12pt;">TOTAL:</td>

      <td style="font-size:12pt;text-align:right;">$ ' . number_format($cotizacion->obtener_total_price(), 2) . '</td>
    </tr>';
      $html .= '</table>';
  }
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
  $mpdf->Output(preg_replace('/[^a-z0-9-_\-\.]/i','_', $cotizacion-> obtener_email_code()) . '(items_table).pdf', 'I');
} catch (\Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
?>
