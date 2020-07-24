<?php
class ExcelRepository{
  public static function print_items($connection, $spreadsheet, $providers_name, $requote_providers_name, $requote, $id_rfq){
    $i = 2;
    $j = 1;
    $quote = RepositorioRfq::obtener_cotizacion_por_id($connection, $id_rfq);
    $items = RepositorioItem::obtener_items_por_id_rfq($connection, $id_rfq);
    $requote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote($connection, $requote-> get_id());

    foreach ($items as $key => $item) {
      $requote_item = $requote_items[$key];
      $x = 'A';
      $providers_item = RepositorioProvider::obtener_providers_por_id_item($connection, $item-> obtener_id());
      $requote_providers = ReQuoteProviderRepository::get_re_quote_providers_by_id_re_quote_item($connection, $requote_item-> get_id());
      list($i, $x) = self::print_item($i, $j, $x, $item, $providers_name, $providers_item, $requote_providers_name, $requote_providers, $spreadsheet);

      $subitems = RepositorioSubitem::obtener_subitems_por_id_item($connection, $item-> obtener_id());
      $requote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item($connection, $item-> obtener_id());
      foreach ($subitems as $key1 => $subitem) {
        $requote_subitem = $requote_subitems[$key1];
        $x = 'A';
        $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem($connection, $subitem-> obtener_id());
        $requote_subitem_providers = ReQuoteSubitemProviderRepository::get_re_quote_subitem_providers_by_id_re_quote_subitem($connection, $requote_subitem-> get_id());
        list($i, $x) = self::print_item($i, '', $x, $subitem, $providers_name, $providers_subitem, $requote_providers_name, $requote_subitem_providers, $spreadsheet);
      }
      $j++;
    }
    $x = 'A';
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
    foreach ($providers_name as $key1 => $provider_name) {
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
    }
    foreach ($requote_providers_name as $key1 => $requote_provider_name) {
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
    }
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $quote-> obtener_total_price());$x++;
  }

  public static function print_item($i, $j, $x, $item, $providers_name, $providers, $requote_providers_name, $requote_providers, $spreadsheet){
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $j);$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_description_project());$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_description());$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_part_number());$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_quantity());$x++;
    foreach ($providers_name as $key1 => $provider_name) {
      foreach ($providers as $key2 => $provider) {
        if($provider_name == $provider-> obtener_provider()){
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $provider-> obtener_price());
        }
      }
      $x++;
    }
    foreach ($requote_providers_name as $key1 => $requote_provider_name) {
      foreach ($requote_providers as $key2 => $requote_provider) {
        if($requote_provider_name == $requote_provider-> get_provider()){
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $requote_provider-> get_price());
        }
      }
      $x++;
    }
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_unit_price());$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_total_price());$x++;
    $i++;
    return array($i, $x);
  }
}
?>
