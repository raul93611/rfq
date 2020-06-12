<?php
class ExcelRepository{
  public static function print_items($connection, $spreadsheet, $providers_name, $id_rfq){
    $i = 2;
    $j = 1;
    $items = RepositorioItem::obtener_items_por_id_rfq($connection, $id_rfq);

    foreach ($items as $key => $item) {
      $x = 'A';
      $providers_item = RepositorioProvider::obtener_providers_por_id_item($connection, $item-> obtener_id());
      list($i, $x) = self::print_item($i, $j, $x, $item, $providers_name, $providers_item, $spreadsheet);

      $subitems = RepositorioSubitem::obtener_subitems_por_id_item($connection, $item-> obtener_id());
      foreach ($subitems as $key => $subitem) {
        $x = 'A';
        $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem($connection, $subitem-> obtener_id());
        list($i, $x) = self::print_item($i, '', $x, $subitem, $providers_name, $providers_subitem, $spreadsheet);
      }
      $j++;
    }
  }

  public static function print_item($i, $j, $x, $item, $providers_name, $providers, $spreadsheet){
    $description_project = explode(PHP_EOL, $item-> obtener_description_project());
    $description = explode(PHP_EOL, $item-> obtener_description());
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $j);$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $description_project[0]);$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $description[0]);$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_part_number_project());$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_quantity());$x++;
    foreach ($providers_name as $key1 => $provider_name) {
      foreach ($providers as $key2 => $provider) {
        if($provider_name == $provider-> obtener_provider()){
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $provider-> obtener_price());
        }
      }
      $x++;
    }
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_unit_price());$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_total_price());$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $item-> obtener_comments());
    $i++;
    if(count($description_project) > 1){
      for($c = 1; $c < count($description_project); $c++){
        $x = 'A';
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $description_project[$c]);$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, $description[$c]);$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
        foreach ($providers_name as $key1 => $provider_name) {
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');
          $x++;
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $i, '');
        $i++;
      }
    }
    return array($i, $x);
  }
}
?>
