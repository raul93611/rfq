<?php
class ProposalRepository{
  public static function print_item($item, $limit, $a){
    $item_description = '';
    $html = '';
    $html_item_description = explode('<br />', nl2br($item-> obtener_description()));
    $j = 0;
    while (strlen($item_description) <= $limit && $j <= count($html_item_description)) {
      $item_description .= $html_item_description[$j] . '<br />';
      $j++;
    }
    $html = '<tr>
      <td style="border-bottom: 0;">' . $a . '</td>
      <td style="border-bottom: 0;"><b>Brand name:</b> ' . $item->obtener_brand() . '<br><b>Part number:</b> ' . $item->obtener_part_number() . '<br><b> Item description:</b><br> ';
    $html .= $item_description;
    $html .= '</td>
      <td style="text-align:right;border-bottom: 0;">' .  $item->obtener_quantity() . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($item->obtener_unit_price(), 2) . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($item->obtener_total_price(), 2) . '</td>
      </tr>';

    while ($j <= count($html_item_description) && $j != 0) {
      $item_description = '';
      while (strlen($item_description) <= $limit && $j <= count($html_item_description)) {
        $item_description .= $html_item_description[$j] . '<br />';
        $j++;
      }
      $html .= '
        <tr style="border-top: 0;">
        <td style="border-bottom: 0;border-top: 0;"></td>
        <td style="border-bottom: 0;border-top: 0;">' . $item_description . '</td>
        <td style="border-bottom: 0;border-top: 0;"></td>
        <td style="border-bottom: 0;border-top: 0;"></td>
        <td style="border-bottom: 0;border-top: 0;"></td>
        </tr>
        ';
    }

    Conexion::abrir_conexion();
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    Conexion::cerrar_conexion();
    if(count($subitems)){
      foreach ($subitems as $i => $subitem) {
        $html .= self::print_subitem($subitem, $limit);
      }
    }

    return $html;
  }

  public static function print_subitem($subitem, $limit){
    $subitem_description = '';
    $html = '';
    $html_subitem_description = explode('<br />', nl2br($subitem-> obtener_description()));
    $j = 0;
    while (strlen($subitem_description) <= $limit && $j <= count($html_subitem_description)) {
      $subitem_description .= $html_subitem_description[$j] . '<br />';
      $j++;
    }
    $html = '<tr>
      <td style="border-bottom: 0;"></td>
      <td style="border-bottom: 0;"><b>Brand name:</b> ' . $subitem->obtener_brand() . '<br><b>Part number:</b> ' . $subitem->obtener_part_number() . '<br><b> Item description:</b><br> ';
    $html .= $subitem_description;
    $html .= '</td>
      <td style="text-align:right;border-bottom: 0;">' .  $subitem->obtener_quantity() . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($subitem->obtener_unit_price(), 2) . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($subitem->obtener_total_price(), 2) . '</td>
      </tr>';

    while ($j <= count($html_subitem_description) && $j != 0) {
      $subitem_description = '';
      while (strlen($subitem_description) <= $limit && $j <= count($html_subitem_description)) {
        $subitem_description .= $html_subitem_description[$j] . '<br />';
        $j++;
      }
      $html .= '
        <tr style="border-top: 0;">
        <td style="border-bottom: 0;border-top: 0;"></td>
        <td style="border-bottom: 0;border-top: 0;">' . $subitem_description . '</td>
        <td style="border-bottom: 0;border-top: 0;"></td>
        <td style="border-bottom: 0;border-top: 0;"></td>
        <td style="border-bottom: 0;border-top: 0;"></td>
        </tr>
        ';
    }

    return $html;
  }

  public static function print_item_pdf($quote, $item, $a, $payment_terms){
    $html = '<tr>
      <td>' . $a . '</td>
      <td><b>Brand name:</b> ' . $item-> obtener_brand_project() . '<br><b>Part number:</b> ' . $item-> obtener_part_number_project() . '<br><b>Item description:</b> ' . nl2br(wordwrap(mb_substr($item-> obtener_description_project(), 0, 150), 70, '<br>', true)) . '</td>
      <td><b>Brand name:</b> ' . $item->obtener_brand() . '<br><b>Part number:</b> ' . $item->obtener_part_number() . '<br><b> Item description:</b><br> ' . nl2br(wordwrap(mb_substr($item->obtener_description(), 0, 150), 70, '<br>', true)) . '</td>
      <td style="text-align:right;">' . $item->obtener_quantity() . '</td>
      <td>';
    Conexion::abrir_conexion();
    $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    Conexion::cerrar_conexion();
    if(count($providers)){
      Conexion::abrir_conexion();
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
      $best_unit_price = $provider_menor-> obtener_price()*$payment_terms*(1+($quote-> obtener_taxes()/100)) + (float)$item-> obtener_additional() + (float)$quote-> obtener_additional();
      $html .= number_format($best_unit_price, 2);
      $html .= '</td>
      <td>$ ' . number_format(round($best_unit_price, 2) * $item-> obtener_quantity(), 2) . '</td>
      <td style="text-align:right;">$ ' . number_format($item->obtener_unit_price(), 2) . '</td>
      <td style="text-align:right;">$ ' . number_format($item->obtener_total_price(), 2) . '</td>
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
    $html .= '</tr>';
    Conexion::abrir_conexion();
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    Conexion::cerrar_conexion();
    foreach ($subitems as $key => $subitem) {
      $html .= self::print_subitem_pdf($quote, $subitem, $payment_terms);
    }

    return $html;
  }

  public static function print_subitem_pdf($quote, $subitem, $payment_terms){
    $html = '
      <tr>
      <td></td>
      <td><b>Brand name:</b> ' . $subitem-> obtener_brand_project() . '<br><b>Part number:</b> ' . $subitem-> obtener_part_number_project() . '<br><b>Item description:</b><br> ' . nl2br(wordwrap(mb_substr($subitem->obtener_description_project(), 0, 150), 70, '<br>', true)) . '</td>}
      <td><b>Brand name:</b> ' . $subitem->obtener_brand() . '<br><b>Part number:</b> ' . $subitem->obtener_part_number() . '<br><b> Item description:</b><br> ' . nl2br(wordwrap(mb_substr($subitem->obtener_description(), 0, 150), 70, '<br>', true)) . '</td>
      <td style="text-align:right;">' . $subitem-> obtener_quantity() . '</td>';
      Conexion::abrir_conexion();
      $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
      Conexion::cerrar_conexion();
      if(count($providers_subitem)){
        $html .= '<td>';
        Conexion::abrir_conexion();
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
        $best_unit_price = $provider_subitem_menor-> obtener_price()*$payment_terms*(1+($quote-> obtener_taxes()/100)) + $subitem-> obtener_additional() + $quote-> obtener_additional();
        $html .= number_format($best_unit_price, 2);
        $html .= '</td>
        <td>$ ' . number_format(round($best_unit_price, 2) * $subitem-> obtener_quantity(), 2) . '</td>
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
      $html .= '</tr>';

    return $html;
  }

  public static function print_item_pdf_re_quote($re_quote_item, $items, $a, $i){
    $item = $items[$i];
    $html = '
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
        $ ' . number_format($re_quote_provider-> get_price(), 2) . '<br>
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
      <td>$ ' . number_format(round($best_unit_price, 2) * $re_quote_item-> get_quantity(), 2) . '</td>';
      if(!is_null($item)){
        $html .=
        '<td style="text-align:right;">$ ' . number_format($item-> obtener_unit_price(), 2) . '</td>
        <td style="text-align:right;">$ ' . number_format($item-> obtener_total_price(), 2) . '</td>
        ';
      }else{
        $html .=
        '<td style="text-align:right;"></td>
        <td style="text-align:right;"></td>
        ';
      }
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
    if(!is_null($item)){
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    }else{
      $subitems = [];
    }
    Conexion::cerrar_conexion();
    foreach ($re_quote_subitems as $key => $re_quote_subitem) {
      $html .= self::print_subitem_pdf_re_quote($re_quote_subitem, $key, $subitems);
    }

    return $html;
  }

  public static function print_subitem_pdf_re_quote($re_quote_subitem, $j, $subitems){
    $subitem = $subitems[$j];
    $html = '
    <tr>
    <td></td>
    <td><b>Brand name:</b>' . $re_quote_subitem-> get_brand_project() . '<br><b>Part number:</b>' . $re_quote_subitem-> get_part_number_project() . '<br><b>Item description:</b><br>' . nl2br(mb_substr($re_quote_subitem-> get_description_project(), 0, 150)) . '</td>
    <td><b>Brand name:</b>' . $re_quote_subitem-> get_brand() . '<br><b>Part number:</b> ' . $re_quote_subitem-> get_part_number() . '<br><b> Item description:</b><br> ' . nl2br(mb_substr($re_quote_subitem-> get_description(), 0, 150)) . '</td>
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
          $ ' . $re_quote_subitem_provider-> get_price() . '<br>
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
        <td>$ ' . number_format(round($best_unit_price, 2) * $re_quote_subitem-> get_quantity(), 2) . '</td>';
        if(!is_null($subitem)){
          $html .=
          '<td style="text-align:right;">$ ' . number_format($subitem-> obtener_unit_price(), 2) . '</td>
          <td style="text-align:right;">$ ' . number_format($subitem-> obtener_total_price(), 2) . '</td>
          ';
        }else{
          $html .=
          '<td style="text-align:right;"></td>
          <td style="text-align:right;"></td>
          ';
        }
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

    return $html;
  }

  public static function print_service_pdf_re_quote($re_quote_payment_term, $payment_term, $re_quote_service, $services, $i){
    $payment_term = $payment_term == 'Net 30/CC' ? 1.03 : 1;
    $re_quote_payment_term = $re_quote_payment_term == 'Net 30/CC' ? 1.03 : 1;
    $service = $services[$i];
    $html = '
    <tr>
      <td>' . $i . '</td>
      <td>' . nl2br($re_quote_service-> get_description()) . '</td>
      <td style="text-align:right;">' . $re_quote_service-> get_quantity() . '</td>
      <td>$ ' . number_format($re_quote_service-> get_unit_price() * $re_quote_payment_term, 2) . '</td>
      <td>$ ' . number_format($re_quote_service-> get_total_price(), 2) . '</td>
    </tr>
    ';

    return $html;
  }

  public static function print_service($payment_term, $service, $a){
    $payment_term = $payment_term == 'Net 30/CC' ? 1.03 : 1;
    $html = '<tr>
      <td style="border-bottom: 0;">' . $a . '</td>
      <td style="border-bottom: 0;">' . nl2br($service-> get_description()) . '</td>
      <td style="text-align:right;border-bottom: 0;">' .  $service-> get_quantity() . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($service-> get_unit_price() * $payment_term, 2) . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($service-> get_total_price(), 2) . '</td>
      </tr>';
    return $html;
  }
}
?>
