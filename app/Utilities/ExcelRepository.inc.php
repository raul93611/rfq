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
      $requote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item($connection, $requote_item-> get_id());
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

  public static function profit_report($connection, $month, $year, $spreadsheet){
    $quotes = Report::get_profit_report($connection, $month, $year);
    $total['total_cost']= 0;
    $total['total_price']= 0;
    $total['re_quote_total_cost']= 0;
    $total['fulfillment_total_cost']= 0;

    $y = 3;
    foreach ($quotes as $key => $quote) {
      $x = 'A';
      $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($connection, $quote-> obtener_id());
      $total_services = ServiceRepository::get_total($connection, $quote-> obtener_id());
      $total['total_cost'] += $quote-> obtener_total_cost();
      $total['total_price'] += $quote-> obtener_total_price() + $total_services;
      $total['re_quote_total_cost'] += $re_quote-> get_total_cost();
      $total['fulfillment_total_cost'] += $quote-> obtener_total_fulfillment() + $quote-> obtener_total_services_fulfillment();

      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, RepositorioComment::mysql_date_to_english_format($quote-> obtener_invoice_date()));$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_id());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_total_cost());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_total_price() + $total_services);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_total_price() + $total_services - $quote-> obtener_total_cost() . "\n" . number_format(100*(($quote-> obtener_total_price() + $total_services - $quote-> obtener_total_cost())/($total_services + $quote-> obtener_total_price())), 2) . '%');
      $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $re_quote-> get_total_cost());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_total_price() + $total_services);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_total_price() + $total_services - $re_quote-> get_total_cost() . "\n" . number_format(100*(($quote-> obtener_total_price() + $total_services - $re_quote-> get_total_cost())/($quote-> obtener_total_price() + $total_services)), 2) . '%');
      $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_total_fulfillment() + $quote-> obtener_total_services_fulfillment());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_total_price() + $total_services);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, (!is_null($quote-> obtener_services_fulfillment_profit()) || !is_null($quote-> obtener_fulfillment_profit()) ? (double)$quote-> obtener_services_fulfillment_profit() + (double)$quote-> obtener_fulfillment_profit() : '0') . "\n" . (!is_null($quote-> obtener_services_fulfillment_profit()) || !is_null($quote-> obtener_fulfillment_profit()) ? number_format(100*(((double)$quote-> obtener_services_fulfillment_profit() + (double)$quote-> obtener_fulfillment_profit())/($quote-> obtener_total_price() + $total_services)), 2) . '%' : '0'));
      $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_type_of_bid() == 'Services' ? 'RFP' : 'RFQ');
      $y++;
    }
    $x = 'C';
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_price'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total_profit = $total['total_price'] - $total['total_cost'], 2) . "\n" . number_format(100*($total_profit/$total['total_price']), 2) . '%');
    $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['re_quote_total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_price'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($re_quote_total_profit = $total['total_price'] - $total['re_quote_total_cost'], 2) . "\n" . number_format(100*($re_quote_total_profit/$total['total_price']), 2) . '%');
    $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['fulfillment_total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_price'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($fulfillment_total_profit = $total['total_price'] - $total['fulfillment_total_cost'], 2) . "\n" . number_format(100*($fulfillment_total_profit/$total['total_price']), 2) . '%');
    $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
  }
}
?>
