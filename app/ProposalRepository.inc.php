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
}
?>
