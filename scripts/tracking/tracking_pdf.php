<?php
include_once 'vendor/autoload.php';
Database::open_connection();
$quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
$items = ItemRepository::get_all_by_id_quote(Database::get_connection(), $id_quote);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Database::get_connection(), $id_quote);
$re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote(Database::get_connection(), $re_quote-> get_id());
Database::close_connection();
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
  <h2 class="color" style="text-align:center;">TRACKING DETAILS</h2>
  <table class="tabla">
    <tr>
      <th style="font-size:9pt;">PROPOSAL #</th>
      <th style="font-size:9pt;">CONTRACT NUMBER</th>
    </tr>
    <tr>
      <td style="text-align:center;font-size:9pt;">' . $quote->get_id() . '</td>
      <td style="text-align:center;font-size:9pt;">' . $quote-> get_contract_number() . '</ts>
    </tr>
  </table>';
  if (count($items)) {
    $html .= '
    <table class="tabla" style="width:100%;">
      <tr>
        <th class="quantity">#</th>
        <th>PROJECT ESPC.</th>
        <th class="quantity">QTY(ordered)</th>
        <th class="quantity">QTY(shipped)</th>
        <th>TRACKING #</th>
        <th>DELIVERY DATE</th>
        <th>SIGNED BY</th>
      </tr>
    ';
    $a = 1;
    for ($i = 0; $i < count($items); $i++) {
      $item = $items[$i];
      $re_quote_item = $re_quote_items[$i];
      Database::open_connection();
      $trackings = TrackingRepository::get_all_trackings_by_id_item(Database::get_connection(), $item-> get_id());
      Database::close_connection();
      if(!count($trackings)){
        $trackings_quantity = 1;
      }else{
        $trackings_quantity = count($trackings);
      }
      $html .= '<tr>
          <td rowspan="' . $trackings_quantity . '">' . $a . '</td>
          <td rowspan="' . $trackings_quantity . '"><b>Brand name:</b> ' . $re_quote_item-> get_brand() . '<br><b>Part number:</b> ' . $re_quote_item-> get_part_number() . '<br><b>Item description:</b> ' . nl2br(wordwrap(mb_substr($re_quote_item-> get_description(), 0, 150), 70, '<br>', true)) . '</td>
          <td rowspan="' . $trackings_quantity . '" style="text-align:right;">' . $re_quote_item-> get_quantity() . '</td>';
      if(count($trackings)){
        $html .= '
        <td>' . $trackings[0]-> get_quantity() . '</td>
        <td>' . $trackings[0]-> get_tracking_number() . '</td>
        <td>' . CommentRepository::mysql_date_to_english_format($trackings[0]-> get_delivery_date()) . '</td>
        <td>' . $trackings[0]-> get_signed_by() . '</td>
        </tr>';
        for ($j = 1; $j < count($trackings); $j++) {
          $tracking = $trackings[$j];
          $html .= '
          <tr>
          <td>' . $tracking-> get_quantity() . '</td>
          <td>' . nl2br($tracking-> get_tracking_number()) . '</td>
          <td>' . CommentRepository::mysql_date_to_english_format($tracking-> get_delivery_date()) . '</td>
          <td>' . $tracking-> get_signed_by() . '</td>
          </tr>
          ';
        }
      }
      Database::open_connection();
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item-> get_id());
      $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Database::get_connection(), $re_quote_item-> get_id());
      Database::close_connection();
      if(count($subitems)){
        for($k = 0; $k < count($subitems); $k++){
          $subitem = $subitems[$k];
          $re_quote_subitem = $re_quote_subitems[$k];
          Database::open_connection();
          $trackings_subitems = TrackingSubitemRepository::get_all_trackings_by_id_subitem(Database::get_connection(), $subitem-> get_id());
          Database::close_connection();
          if(!count($trackings_subitems)){
            $trackings_subitems_quantity = 1;
          }else{
            $trackings_subitems_quantity = count($trackings_subitems);
          }
          $html .= '
          <tr>
          <td rowspan="' . $trackings_subitems_quantity . '"></td>
          <td rowspan="' . $trackings_subitems_quantity . '"><b>Brand name:</b> ' . $re_quote_subitem-> get_brand() . '<br><b>Part number:</b> ' . $re_quote_subitem-> get_part_number() . '<br><b>Item description:</b><br> ' . nl2br(wordwrap(mb_substr($re_quote_subitem-> get_description(), 0, 150), 70, '<br>', true)) . '</td>
          <td rowspan="' . $trackings_subitems_quantity . '" style="text-align:right;">' . $re_quote_subitem-> get_quantity() . '</td>';
          if(count($trackings_subitems)){
            $html .= '
            <td>' . $trackings_subitems[0]-> get_quantity() . '</td>
            <td>' . $trackings_subitems[0]-> get_tracking_number() . '</td>
            <td>' . CommentRepository::mysql_date_to_english_format($trackings_subitems[0]-> get_delivery_date()) . '</td>
            <td>' . $trackings_subitems[0]-> get_signed_by() . '</td>
            </tr>
            ';
            for ($l = 1; $l < count($trackings_subitems); $l++) {
              $tracking_subitem = $trackings_subitems[$l];
              $html .= '
              <tr>
              <td>' . $tracking_subitem-> get_quantity() . '</td>
              <td>' . nl2br($tracking_subitem-> get_tracking_number()) . '</td>
              <td>' . CommentRepository::mysql_date_to_english_format($tracking_subitem-> get_delivery_date()) . '</td>
              <td>' . $tracking_subitem-> get_signed_by() . '</td>
              </tr>
              ';
            }
          }
        }
      }
    $a++;
    }
    $html .= '</table>';
  }
  $mpdf->SetHTMLFooter('
  <div class="color letra_chiquita" style="text-align:center;">
  EIN: 51-0629765, DUNS: 786-965876, CAGE:4QTF4<br>SBA 8(a) and HUBZone certified
  </div>
  ');
  $mpdf->WriteHTML($html);
  $mpdf->Output($_SERVER['DOCUMENT_ROOT'] . '/rfq/documents/' . $quote->get_id() . '/' . preg_replace('/[^a-z0-9-_\-\.]/i','_', $quote-> get_email_code()) . '(trackings)' . '.pdf', 'F');
  $mpdf->Output(preg_replace('/[^a-z0-9-_\-\.]/i','_', $quote-> get_email_code()) . '(trackings).pdf', 'I');
} catch (Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
?>
