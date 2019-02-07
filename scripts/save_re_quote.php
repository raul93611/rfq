<?php
if(isset($_POST['save_re_quote'])){
  Conexion::abrir_conexion();
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $_POST['id_re_quote']);
  $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $re_quote-> get_id_rfq());
  if($cotizacion-> obtener_canal() == 'FedBid'){
    ReQuoteRepository::update_re_quote(Conexion::obtener_conexion(), 0, 0, $_POST['total_cost'], $_POST['total_price'], 0, '', '', 0, $_POST['id_re_quote']);
  }else{
    $id_re_quote_items = explode(',', $_POST['id_re_quote_items']);
    $id_re_quote_subitems = explode(',', $_POST['id_re_quote_subitems']);
    $partes_total_price = explode(',', $_POST['partes_total_price']);
    $partes_total_price_subitems = explode(',', $_POST['partes_total_price_subitems']);
    $unit_prices = explode(',', $_POST['unit_prices']);
    $unit_prices_subitems = explode(',', $_POST['unit_prices_subitems']);
    $additional = explode(',', $_POST['additional']);
    $additional_subitems = explode(',', $_POST['additional_subitems']);

    foreach ($id_re_quote_items as $key => $id_re_quote_item) {
      ReQuoteItemRepository::insert_calc(Conexion::obtener_conexion(), $unit_prices[$key], $partes_total_price[$key], $additional[$key], $id_re_quote_item);
    }

    foreach ($id_re_quote_subitems as $key => $id_re_quote_subitem) {
      ReQuoteSubitemRepository::insert_calc(Conexion::obtener_conexion(), $unit_prices_subitems[$key], $partes_total_price_subitems[$key], $additional_subitems[$key], $id_re_quote_subitem);
    }

    switch($_POST['payment_terms']){
      case 'Net 30':
        $payment_terms = 'Net 30';
        break;
      case 'Net 30/CC':
        $payment_terms = 'Net 30/CC';
        break;
    }

    ReQuoteRepository::update_re_quote(Conexion::obtener_conexion(), $_POST['taxes'], $_POST['profit'], $_POST['total_cost'], $_POST['total_price'], $_POST['additional_general'], $payment_terms, $_POST['shipping'], $_POST['shipping_cost'], $_POST['id_re_quote']);
  }
  Conexion::cerrar_conexion();
  Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq());
}
?>
