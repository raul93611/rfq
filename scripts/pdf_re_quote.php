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
    for ($i = 0; $i < count($re_quote_items); $i++) {
      $re_quote_item = $re_quote_items[$i];
      $item = $items[$i];
      $html .= '
      <tr>
      <td>' . $a . '</td>
      <td><b>Brand name:</b>' . $re_quote_item-> get_brand_project() . '<br><b>Part number:</b>' . $re_quote_item-> get_part_number_project() . '<br><b>Item description:</b>' . nl2br(mb_substr($re_quote_item-> get_description_project(), 0, 150)) . '</td>
      <td><b>Brand name:</b>' . $re_quote_item-> get_brand() . '<br><b>Part number:</b>' . $re_quote_item-> get_part_number() . '<br><b> Item description:</b><br>' . nl2br(mb_substr($re_quote_item-> get_description(), 0, 150)) . '</td>
      <td style="text-align:right;">' . $re_quote_item-> get_quantity() . '</td>
      <td style="width: 200px;">
      ';
      Conexion::abrir_conexion();
      $re_quote_providers = ReQuoteProviderRepository::get_re_quote_providers_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item-> get_id());
      Conexion::cerrar_conexion();
      $prices = [];
      if(count($re_quote_providers)){
        foreach ($re_quote_providers as $re_quote_provider) {
          $prices[] = $re_quote_provider-> get_price();
          $html .= '
          <b>' . $re_quote_provider-> get_provider() . ':</b><br>
          $ ' . number_format($re_quote_provider-> get_price(), 2) . '
          ';
        }
        $html .= '
        </td>
        <td>$
        ';
        $best_unit_price = min($prices);
        $html .= number_format($best_unit_price, 2);
        $html .= '
        </td>
        <td>$ ' . number_format(round($best_unit_price, 2) * $re_quote_item-> get_quantity(), 2) . '</td>
        <td style="text-align:right;">$ ' . number_format($item-> obtener_unit_price(), 2) . '</td>
        <td style="text-align:right;">$ ' . number_format($item-> obtener_total_price(), 2) . '</td>
        ';
      }else{
        $html .= '
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        ';
      }
      $html .= '
      </tr>
      ';
      Conexion::abrir_conexion();
      $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item-> get_id());
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
      Conexion::cerrar_conexion();
      for($j = 0; $j < count($re_quote_subitems); $j++){
        $re_quote_subitem = $re_quote_subitems[$j];
        $subitem = $subitems[$j];
        $html .= '
        <tr>
        <td></td>
        <td><b>Brand name:</b>' . $re_quote_subitem-> get_brand_project() . '<br><b>Part number:</b>' . $re_quote_subitem-> get_part_number_project() . '<br><b>Item description:</b><br>' . nl2br(mb_substr($re_quote_subitem-> get_description_project(), 0, 150)) . '</td>
        <td><b>Brand name:</b>' . $re_quote_subitem-> get_brand() . '<br><b>Part number:</b> ' . $re_quote_item-> get_part_number() . '<br><b> Item description:</b><br> ' . nl2br(mb_substr($re_quote_item-> get_description(), 0, 150)) . '</td>
        <td style="text-align:right;">' . $re_quote_subitem-> get_quantity() . '</td>
        ';
          Conexion::abrir_conexion();
          $re_quote_subitem_providers = ReQuoteSubitemProviderRepository::get_re_quote_subitem_providers_by_id_re_quote_subitem(Conexion::obtener_conexion(), $re_quote_subitem-> get_id());
          Conexion::cerrar_conexion();
          if(count($re_quote_subitem_providers)){
            $html .= '
            <td>
            ';
            $prices = [];
            if(count($re_quote_subitem_providers)){
              foreach ($re_quote_subitem_providers as $re_quote_subitem_provider) {
                $prices[] = $re_quote_subitem_provider-> get_price();
                $html .= '
                <b>' . $re_quote_subitem_provider-> get_provider() . ':</b><br>
                $ ' . $re_quote_subitem_provider-> get_price() . '
                ';
              }
            }
            $html .= '
            </td>
            ';
            $html .= '
            <td>$
            ';
              $best_unit_price = min($prices);
              $html .= number_format($best_unit_price, 2);
              $html .= '
              </td>
              <td>$ ' . number_format(round($best_unit_price, 2) * $re_quote_subitem-> get_quantity(), 2) . '</td>
              <td style="text-align:right;">$ ' . number_format($subitem-> obtener_unit_price(), 2) . '</td>
              <td style="text-align:right;">$ ' . number_format($subitem-> obtener_total_price(), 2) . '</td>
              ';
            }else{
              $html .= '
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              ';
            }
            $html .= '
            </tr>
            ';
          }
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
