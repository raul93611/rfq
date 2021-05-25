<?php
class ProposalRepository{
  public static function print_item($item, $limit, $a){
    $item_description = '';
    $html = '';
    $html_item_description = explode('<br />', nl2br($item-> get_description()));
    $j = 0;
    while (strlen($item_description) <= $limit && $j <= count($html_item_description)) {
      $item_description .= $html_item_description[$j] . '<br />';
      $j++;
    }
    $html = '<tr>
      <td style="border-bottom: 0;">' . $a . '</td>
      <td style="border-bottom: 0;"><b>Brand name:</b> ' . $item->get_brand() . '<br><b>Part number:</b> ' . $item->get_part_number() . '<br><b> Item description:</b><br> ';
    $html .= $item_description;
    $html .= '</td>
      <td style="text-align:right;border-bottom: 0;">' .  $item->get_quantity() . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($item->get_unit_price(), 2) . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($item->get_total_price(), 2) . '</td>
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

    Database::open_connection();
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item-> get_id());
    Database::close_connection();
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
    $html_subitem_description = explode('<br />', nl2br($subitem-> get_description()));
    $j = 0;
    while (strlen($subitem_description) <= $limit && $j <= count($html_subitem_description)) {
      $subitem_description .= $html_subitem_description[$j] . '<br />';
      $j++;
    }
    $html = '<tr>
      <td style="border-bottom: 0;"></td>
      <td style="border-bottom: 0;"><b>Brand name:</b> ' . $subitem->get_brand() . '<br><b>Part number:</b> ' . $subitem->get_part_number() . '<br><b> Item description:</b><br> ';
    $html .= $subitem_description;
    $html .= '</td>
      <td style="text-align:right;border-bottom: 0;">' .  $subitem->get_quantity() . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($subitem->get_unit_price(), 2) . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($subitem->get_total_price(), 2) . '</td>
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
      <td><b>Brand name:</b> ' . $item-> get_brand_project() . '<br><b>Part number:</b> ' . $item-> get_part_number_project() . '<br><b>Item description:</b> ' . nl2br(wordwrap(mb_substr($item-> get_description_project(), 0, 150), 70, '<br>', true)) . '</td>
      <td><b>Brand name:</b> ' . $item->get_brand() . '<br><b>Part number:</b> ' . $item->get_part_number() . '<br><b> Item description:</b><br> ' . nl2br(wordwrap(mb_substr($item->get_description(), 0, 150), 70, '<br>', true)) . '</td>
      <td style="text-align:right;">' . $item->get_quantity() . '</td>
      <td>';
    Database::open_connection();
    $providers = ProviderRepository::get_all_by_id_item(Database::get_connection(), $item-> get_id());
    Database::close_connection();
    if(count($providers)){
      Database::open_connection();
      $least_provider = ProviderRepository::get_by_id(Database::get_connection(), $item-> get_least_provider());
      Database::close_connection();
      if(count($providers)){
        foreach ($providers as $provider) {
          $html .= '<b>' . $provider-> get_provider() . ':</b><br>$ ' . number_format($provider-> get_price(), 2) . '<br>';
        }
      }
      $html .= '
      </td>
      <td>$ ' . number_format($item-> get_additional(), 2) . '</td>
      <td>$ ';
      $best_unit_price = $least_provider-> get_price()*$payment_terms*(1+($quote-> get_taxes()/100)) + (float)$item-> get_additional() + (float)$quote-> get_additional();
      $html .= number_format($best_unit_price, 2);
      $html .= '</td>
      <td>$ ' . number_format(round($best_unit_price, 2) * $item-> get_quantity(), 2) . '</td>
      <td style="text-align:right;">$ ' . number_format($item->get_unit_price(), 2) . '</td>
      <td style="text-align:right;">$ ' . number_format($item->get_total_price(), 2) . '</td>
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
    Database::open_connection();
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item-> get_id());
    Database::close_connection();
    foreach ($subitems as $key => $subitem) {
      $html .= self::print_subitem_pdf($quote, $subitem, $payment_terms);
    }

    return $html;
  }

  public static function print_subitem_pdf($quote, $subitem, $payment_terms){
    $html .= '
      <tr>
      <td></td>
      <td><b>Brand name:</b> ' . $subitem-> get_brand_project() . '<br><b>Part number:</b> ' . $subitem-> get_part_number_project() . '<br><b>Item description:</b><br> ' . nl2br(wordwrap(mb_substr($subitem->get_description_project(), 0, 150), 70, '<br>', true)) . '</td>}
      <td><b>Brand name:</b> ' . $subitem->get_brand() . '<br><b>Part number:</b> ' . $subitem->get_part_number() . '<br><b> Item description:</b><br> ' . nl2br(wordwrap(mb_substr($subitem->get_description(), 0, 150), 70, '<br>', true)) . '</td>
      <td style="text-align:right;">' . $subitem-> get_quantity() . '</td>';
      Database::open_connection();
      $providers_subitem = ProviderSubitemRepository::get_all_by_id_subitem(Database::get_connection(), $subitem-> get_id());
      Database::close_connection();
      if(count($providers_subitem)){
        $html .= '<td>';
        Database::open_connection();
        $provider_subitem_menor = ProviderSubitemRepository::get_by_id(Database::get_connection(), $subitem-> get_least_provider());
        Database::close_connection();
        if(count($providers_subitem)){
          foreach ($providers_subitem as $provider_subitem) {
            $html .= '<b>' . $provider_subitem-> get_provider()  . ':</b><br>$ ' . number_format($provider_subitem-> get_price(), 2) . '<br>';
          }
        }
        $html .= '
        </td>
        <td>$ ' . number_format($subitem-> get_additional(), 2) . '</td>
        <td>$ ';
        $best_unit_price = $provider_subitem_menor-> get_price()*$payment_terms*(1+($quote-> get_taxes()/100)) + $subitem-> get_additional() + $quote-> get_additional();
        $html .= number_format($best_unit_price, 2);
        $html .= '</td>
        <td>$ ' . number_format(round($best_unit_price, 2) * $subitem-> get_quantity(), 2) . '</td>
        <td style="text-align:right;">$ ' . number_format($subitem-> get_unit_price(), 2) . '</td>
        <td style="text-align:right;">$ ' . number_format($subitem-> get_total_price(), 2) . '</td>
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
    $html .= '
    <tr>
    <td>' . $a . '</td>
    <td><b>Brand name:</b>' . $re_quote_item-> get_brand_project() . '<br><b>Part number:</b>' . $re_quote_item-> get_part_number_project() . '<br><b>Item description:</b>' . nl2br(mb_substr($re_quote_item-> get_description_project(), 0, 150)) . '</td>
    <td><b>Brand name:</b>' . $re_quote_item-> get_brand() . '<br><b>Part number:</b>' . $re_quote_item-> get_part_number() . '<br><b> Item description:</b><br>' . nl2br(mb_substr($re_quote_item-> get_description(), 0, 150)) . '</td>
    <td style="text-align:right;">' . $re_quote_item-> get_quantity() . '</td>
    <td style="width: 200px;">
    ';
    Database::open_connection();
    $re_quote_providers = ReQuoteProviderRepository::get_re_quote_providers_by_id_re_quote_item(Database::get_connection(), $re_quote_item-> get_id());
    Database::close_connection();
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
        '<td style="text-align:right;">$ ' . number_format($item-> get_unit_price(), 2) . '</td>
        <td style="text-align:right;">$ ' . number_format($item-> get_total_price(), 2) . '</td>
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
    Database::open_connection();
    $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Database::get_connection(), $re_quote_item-> get_id());
    if(!is_null($item)){
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item-> get_id());
    }else{
      $subitems = [];
    }
    Database::close_connection();
    foreach ($re_quote_subitems as $key => $re_quote_subitem) {
      $html .= self::print_subitem_pdf_re_quote($re_quote_subitem, $key, $subitems);
    }

    return $html;
  }

  public static function print_subitem_pdf_re_quote($re_quote_subitem, $j, $subitems){
    $subitem = $subitems[$j];
    $html .= '
    <tr>
    <td></td>
    <td><b>Brand name:</b>' . $re_quote_subitem-> get_brand_project() . '<br><b>Part number:</b>' . $re_quote_subitem-> get_part_number_project() . '<br><b>Item description:</b><br>' . nl2br(mb_substr($re_quote_subitem-> get_description_project(), 0, 150)) . '</td>
    <td><b>Brand name:</b>' . $re_quote_subitem-> get_brand() . '<br><b>Part number:</b> ' . $re_quote_subitem-> get_part_number() . '<br><b> Item description:</b><br> ' . nl2br(mb_substr($re_quote_subitem-> get_description(), 0, 150)) . '</td>
    <td style="text-align:right;">' . $re_quote_subitem-> get_quantity() . '</td>
    ';
    Database::open_connection();
    $re_quote_subitem_providers = ReQuoteSubitemProviderRepository::get_re_quote_subitem_providers_by_id_re_quote_subitem(Database::get_connection(), $re_quote_subitem-> get_id());
    Database::close_connection();
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
          '<td style="text-align:right;">$ ' . number_format($subitem-> get_unit_price(), 2) . '</td>
          <td style="text-align:right;">$ ' . number_format($subitem-> get_total_price(), 2) . '</td>
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

  public static function print_service($service, $a){
    $html = '<tr>
      <td style="border-bottom: 0;">' . $a . '</td>
      <td style="border-bottom: 0;">' . nl2br($service-> get_description()) . '</td>
      <td style="text-align:right;border-bottom: 0;">' .  $service-> get_quantity() . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($service-> get_unit_price(), 2) . '</td>
      <td style="text-align:right;border-bottom: 0;">$ ' . number_format($service-> get_total_price(), 2) . '</td>
      </tr>';
    return $html;
  }
}
?>
