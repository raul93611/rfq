<?php
class ExcelRepository{
  public static function print_tracking($connection, $spreadsheet, $quote, $re_quote){
    $items = RepositorioItem::obtener_items_por_id_rfq($connection, $quote-> obtener_id());
    $re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote($connection, $re_quote-> get_id());

    $y = 5;
    $a = 1;
    foreach ($items as $key => $item) {
      $re_quote_item = $re_quote_items[$key];
      $trackings = TrackingRepository::get_all_trackings_by_id_item($connection, $item-> obtener_id());
      $x = 'A';
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $a);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, "Brand name: " . $re_quote_item-> get_brand() . "\n Part number: " . $re_quote_item-> get_part_number() . "\n Description: " . mb_substr($re_quote_item-> get_description(), 0, 150));
      $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $re_quote_item-> get_quantity());$x++;
      if(count($trackings)){
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings[0]-> get_quantity());$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings[0]-> get_carrier());$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings[0]-> get_tracking_number());$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, RepositorioComment::mysql_date_to_english_format($trackings[0]-> get_delivery_date()));$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, RepositorioComment::mysql_date_to_english_format($trackings[0]-> get_due_date()));$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings[0]-> get_signed_by());$x++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings[0]-> get_comments());$x++;
        for ($i=1; $i < count($trackings); $i++) {
          $y++;
          $x = 'D';
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings[$i]-> get_quantity());$x++;
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings[$i]-> get_carrier());$x++;
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings[$i]-> get_tracking_number());$x++;
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, RepositorioComment::mysql_date_to_english_format($trackings[$i]-> get_delivery_date()));$x++;
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, RepositorioComment::mysql_date_to_english_format($trackings[$i]-> get_due_date()));$x++;
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings[$i]-> get_signed_by());$x++;
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings[$i]-> get_comments());$x++;
        }
      }
      $y++;
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item($connection, $item-> obtener_id());
      $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item($connection, $re_quote_item-> get_id());
      if(count($subitems)){
        foreach ($subitems as $key => $subitem){
          $x = 'A';

          $re_quote_subitem = $re_quote_subitems[$key];
          $trackings_subitems = TrackingSubitemRepository::get_all_trackings_by_id_subitem($connection, $subitem-> obtener_id());
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, '');$x++;
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, "Brand name: " . $re_quote_subitem-> get_brand() . "\n Part number: " . $re_quote_subitem-> get_part_number() . "\n Description: " . mb_substr($re_quote_subitem-> get_description(), 0, 150));
          $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
          $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $re_quote_subitem-> get_quantity());$x++;

          if(count($trackings_subitems)){
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings_subitems[0]-> get_quantity());$x++;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings_subitems[0]-> get_carrier());$x++;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings_subitems[0]-> get_tracking_number());$x++;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, RepositorioComment::mysql_date_to_english_format($trackings_subitems[0]-> get_delivery_date()));$x++;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, RepositorioComment::mysql_date_to_english_format($trackings_subitems[0]-> get_due_date()));$x++;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings_subitems[0]-> get_signed_by());$x++;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings_subitems[0]-> get_comments());$x++;
            for ($i = 1; $i < count($trackings_subitems); $i++) {
              $y++;
              $x = 'D';
              $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings_subitems[$i]-> get_quantity());$x++;
              $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings_subitems[$i]-> get_carrier());$x++;
              $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings_subitems[$i]-> get_tracking_number());$x++;
              $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, RepositorioComment::mysql_date_to_english_format($trackings_subitems[$i]-> get_delivery_date()));$x++;
              $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, RepositorioComment::mysql_date_to_english_format($trackings_subitems[$i]-> get_due_date()));$x++;
              $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings_subitems[$i]-> get_signed_by());$x++;
              $spreadsheet->setActiveSheetIndex(0)->setCellValue($x . $y, $trackings_subitems[$i]-> get_comments());$x++;
            }
          }
          $y++;
        }
      }
      $a++;
    }
  }

  public static function print_items($connection, $spreadsheet, $providers_name, $requote_providers_name, $requote, $id_rfq){
    $i = 3;
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

  public static function profit_report($connection, $type, $quarter, $month, $year, $spreadsheet){
    $quotes = Report::get_profit_report($connection, $type, $quarter, $month, $year);
    $total['total_cost']= 0;
    $total['total_price']= 0;
    $total['re_quote_total_cost']= 0;
    $total['fulfillment_total_cost']= 0;

    $y = 3;
    foreach ($quotes as $key => $quote) {
      $x = 'A';
      $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($connection, $quote-> obtener_id());
      $total['total_cost'] += $quote-> obtener_total_cost();
      $total['total_price'] += $quote-> obtener_quote_total_price();
      $total['re_quote_total_cost'] += $re_quote-> get_total_cost();
      $total['fulfillment_total_cost'] += $quote-> obtener_total_fulfillment() + $quote-> obtener_total_services_fulfillment();

      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, RepositorioComment::mysql_date_to_english_format($quote-> obtener_invoice_date()));$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_id());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_total_cost());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_quote_total_price());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_quote_profit() . "\n" . number_format($quote-> obtener_quote_profit_percentage(), 2) . '%');
      $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $re_quote-> get_total_cost());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_quote_total_price());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_quote_total_price() - $re_quote-> get_total_cost() . "\n" . number_format(100*(($quote-> obtener_quote_total_price() - $re_quote-> get_total_cost())/$quote-> obtener_quote_total_price()), 2) . '%');
      $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_total_fulfillment() + $quote-> obtener_total_services_fulfillment());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_quote_total_price());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($quote-> obtener_real_fulfillment_profit(), 2) . "\n" . number_format($quote-> obtener_real_fulfillment_profit_percentage(), 2));
      $spreadsheet->getActiveSheet()->getStyle($x.$y)->getAlignment()->setWrapText(true);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_type_of_contract());
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

  public static function getAwardReport(
    $connection,
    $type,
    $quarter,
    $month,
    $year
  ) {
    $data = [];
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_award, '%m/%d/%Y') as fecha_award, 
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit, 
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage,
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            MONTH(fecha_award) = {$month} AND 
            YEAR(fecha_award) = {$year} 
            GROUP BY r.id";
            break;
          case 'quarterly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_award, '%m/%d/%Y') as fecha_award,  
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage,
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            QUARTER(fecha_award) = {$quarter} AND
            YEAR(fecha_award) = {$year} 
            GROUP BY r.id";
            break;
          case 'yearly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_award, '%m/%d/%Y') as fecha_award,  
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage,
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            YEAR(fecha_award) = {$year} 
            GROUP BY r.id";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function awardReport($connection, $type, $quarter, $month, $year, $spreadsheet){
    $quotes = self::getAwardReport($connection, $type, $quarter, $month, $year);
    $total['total_cost']= 0;
    $total['total_price']= 0;

    $y = 2;
    foreach ($quotes as $key => $quote) {
      $x = 'A';
      $total['total_cost'] += $quote['total_cost'];
      $total['total_price'] += $quote['total_price'];

      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['id']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['fecha_award']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['contract_number']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['email_code']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['nombre_usuario']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['canal']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['type_of_bid']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['total_cost']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['total_price']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['profit']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($quote['profit_percentage'], 2) . '%');$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['type_of_contract']);
      $y++;
    }
    $x = 'H';
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_price'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total_profit = $total['total_price'] - $total['total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format(100*($total_profit/$total['total_price']), 2) . '%');$x++;
  }

  public static function getSubmittedReport(
    $connection,
    $type,
    $quarter,
    $month,
    $year
  ) {
    $data = [];
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_submitted, '%m/%d/%Y') as fecha_submitted, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage,
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            MONTH(fecha_submitted) = {$month} AND 
            YEAR(fecha_submitted) = {$year}  
            GROUP BY r.id";
            break;
          case 'quarterly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_submitted, '%m/%d/%Y') as fecha_submitted,  
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage,
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            QUARTER(fecha_submitted) = {$quarter} AND
            YEAR(fecha_submitted) = {$year}
            GROUP BY r.id";
            break;
          case 'yearly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_submitted, '%m/%d/%Y') as fecha_submitted,  
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage,
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            YEAR(fecha_submitted) = {$year} 
            GROUP BY r.id";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function submittedReport($connection, $type, $quarter, $month, $year, $spreadsheet){
    $quotes = self::getSubmittedReport($connection, $type, $quarter, $month, $year);
    $total['total_cost']= 0;
    $total['total_price']= 0;

    $y = 2;
    foreach ($quotes as $key => $quote) {
      $x = 'A';
      $total['total_cost'] += $quote['total_cost'];
      $total['total_price'] += $quote['total_price'];

      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['fecha_submitted']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['id']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['email_code']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['nombre_usuario']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['canal']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['type_of_bid']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['total_cost']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['total_price']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['profit']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($quote['profit_percentage'], 2) . '%');$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['type_of_contract']);
      $y++;
    }
    $x = 'G';
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_price'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total_profit = $total['total_price'] - $total['total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format(100*($total_profit/$total['total_price']), 2) . '%');$x++;
  }

  public static function getFulfillmentReport(
    $connection,
    $type,
    $quarter,
    $month,
    $year
  ) {
    $data = [];
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') as fulfillment_date, 
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit, 
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage,
            COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) AS total_cost_requote,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price_requote,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit_requote,
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage_requote,
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            LEFT JOIN usuarios u ON r.usuario_designado = u.id
            LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            MONTH(fulfillment_date) = {$month} AND 
            YEAR(fulfillment_date) = {$year} 
            GROUP BY r.id";
            break;
          case 'quarterly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') as fulfillment_date,  
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit, 
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage,
            COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) AS total_cost_requote,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price_requote,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit_requote,
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage_requote, 
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            LEFT JOIN usuarios u ON r.usuario_designado = u.id
            LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            QUARTER(fulfillment_date) = {$quarter} AND
            YEAR(fulfillment_date) = {$year} 
            GROUP BY r.id";
            break;
          case 'yearly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') as fulfillment_date,  
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit, 
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage,
            COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) AS total_cost_requote,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price_requote,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit_requote,
            ((COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0)) / NULLIF(COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0), 0)) * 100 AS profit_percentage_requote, 
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            LEFT JOIN usuarios u ON r.usuario_designado = u.id
            LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            YEAR(fulfillment_date) = {$year} 
            GROUP BY r.id";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function fulfillmentReport($connection, $type, $quarter, $month, $year, $spreadsheet){
    $quotes = self::getFulfillmentReport($connection, $type, $quarter, $month, $year);
    $total['total_cost']= 0;
    $total['total_price']= 0;
    $total['re_quote_total_cost']= 0;

    $y = 3;
    foreach ($quotes as $key => $quote) {
      $x = 'A';
      $total['total_cost'] += $quote['total_cost'];
      $total['total_price'] += $quote['total_price'];
      $total['re_quote_total_cost'] += $quote['total_cost_requote'];

      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['fulfillment_date']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['id']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['nombre_usuario']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['canal']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['email_code']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['type_of_bid']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['contract_number']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['total_cost']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['total_cost']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['profit']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($quote['profit_percentage'], 2) . '%');$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['total_cost_requote']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['total_price']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['profit_requote']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($quote['profit_requote_percentage'], 2) . '%');$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['type_of_contract']);
      $y++;
    }
    $x = 'H';
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_price'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total_profit = $total['total_price'] - $total['total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format(100*($total_profit/$total['total_price']), 2) . '%');$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['re_quote_total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_price'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($re_quote_total_profit = $total['total_price'] - $total['re_quote_total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format(100*($re_quote_total_profit/$total['total_price']), 2) . '%');
  }

  public static function getAccountsPayableFulfillmentReport(
    $connection,
    $type,
    $quarter,
    $month,
    $year
  ) {
    $data = [];
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT id, provider, real_cost, payment_term 
            FROM (
            SELECT r.id, fi.provider, fi.real_cost, fi.payment_term FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fsi.provider, fsi.real_cost, fsi.payment_term FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fs.provider, fs.real_cost, fs.payment_term FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment 
            ORDER BY id;
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT id, provider, real_cost, payment_term 
            FROM (
            SELECT r.id, fi.provider, fi.real_cost, fi.payment_term FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fsi.provider, fsi.real_cost, fsi.payment_term FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fs.provider, fs.real_cost, fs.payment_term FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment 
            ORDER BY id;
            ";
            break;
          case 'yearly':
            $sql = "
            SELECT id, provider, real_cost, payment_term 
            FROM (
            SELECT r.id, fi.provider, fi.real_cost, fi.payment_term FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fsi.provider, fsi.real_cost, fsi.payment_term FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fs.provider, fs.real_cost, fs.payment_term FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment 
            ORDER BY id;
            ";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function accountsPayableFulfillmentReport($connection, $type, $quarter, $month, $year, $spreadsheet){
    $quotes = self::getAccountsPayableFulfillmentReport($connection, $type, $quarter, $month, $year);

    $y = 2;
    foreach ($quotes as $key => $quote) {
      $x = 'A';
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['id']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['provider']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['real_cost']);$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote['payment_term']);$x++;
      $y++;
    }
  }

  public static function fulfillment_pending_report($connection, $spreadsheet){
    $quotes = Report::get_fulfillment_pending_report($connection);
    $total['total_cost']= 0;
    $total['total_price']= 0;
    $total['re_quote_total_cost']= 0;

    $y = 3;
    foreach ($quotes as $key => $quote) {
      $x = 'A';
      $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($connection, $quote-> obtener_id());
      $total['total_cost'] += $quote-> obtener_total_cost();
      $total['total_price'] += $quote-> obtener_quote_total_price();
      $total['re_quote_total_cost'] += $re_quote-> get_total_cost();

      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, RepositorioComment::mysql_date_to_english_format($quote-> obtener_fulfillment_date()));$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_id());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_designated_username());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> print_channel());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_email_code());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_type_of_bid());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, RepositorioComment::mysql_date_to_english_format($quote-> obtener_fecha_award()));$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_contract_number());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_total_cost());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_quote_total_price());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_quote_profit());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($quote-> obtener_quote_profit_percentage(), 2) . '%');$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $re_quote-> get_total_cost());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_quote_total_price());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_quote_total_price() - $re_quote-> get_total_cost());$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format(100*(($quote-> obtener_quote_total_price() - $re_quote-> get_total_cost())/$quote-> obtener_quote_total_price()), 2) . '%');$x++;
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, $quote-> obtener_type_of_contract());
      $y++;
    }
    $x = 'I';
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_price'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total_profit = $total['total_price'] - $total['total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format(100*($total_profit/$total['total_price']), 2) . '%');$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['re_quote_total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($total['total_price'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format($re_quote_total_profit = $total['total_price'] - $total['re_quote_total_cost'], 2));$x++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($x.$y, number_format(100*($re_quote_total_profit/$total['total_price']), 2) . '%');
  }
}
?>
