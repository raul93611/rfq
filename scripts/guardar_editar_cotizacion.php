<?php
session_start();
if (isset($_POST['guardar_cambios_cotizacion'])) {
  Conexion::abrir_conexion();
  $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $id_items = explode(',', $_POST['id_items']);
  $id_subitems = explode(',', $_POST['id_subitems']);
  $partes_total_price = explode(',', $_POST['partes_total_price']);
  $partes_total_price_subitems = explode(',', $_POST['partes_total_price_subitems']);
  $unit_prices = explode(',', $_POST['unit_prices']);
  $unit_prices_subitems = explode(',', $_POST['unit_prices_subitems']);
  $additional = explode(',', $_POST['additional']);
  $additional_subitems = explode(',', $_POST['additional_subitems']);
  for ($i = 0; $i < count($id_items); $i++) {
    RepositorioItem::insertar_calculos(Conexion::obtener_conexion(), $unit_prices[$i], $partes_total_price[$i], $additional[$i], $id_items[$i]);
  }
  for($j = 0; $j < count($id_subitems); $j++){
    RepositorioSubitem::insertar_calculos(Conexion::obtener_conexion(), $unit_prices_subitems[$j], $partes_total_price_subitems[$j], $additional_subitems[$j], $id_subitems[$j]);
  }
  switch($_POST['payment_terms']){
    case 'Net 30':
      $payment_terms = 'Net 30';
      break;
    case 'Net 30/CC':
      $payment_terms = 'Net 30/CC';
      break;
  }
  $cotizacion_editada3 = RepositorioRfq::actualizar_shipping(Conexion::obtener_conexion(), htmlspecialchars($_POST['shipping']), $_POST['shipping_cost'], $_POST['id_rfq']);
  $cotizacion_editada1 = RepositorioRfq::actualizar_taxes_profit(Conexion::obtener_conexion(), $_POST['taxes'], $_POST['profit'], $_POST['total_cost'], $_POST['total_price'], $_POST['additional_general'], $_POST['id_rfq']);
  $cotizacion_editada2 = RepositorioRfq::actualizar_payment_terms(Conexion::obtener_conexion(), $payment_terms, $_POST['id_rfq']);
  /*****************************************************************************************************************************/
  /********************************************************GUARDAR TOTAL_PRICE EN FEDBID****************************************/
  /*****************************************************************************************************************************/
  if($cotizacion_recuperada-> obtener_canal() == 'FedBid'){
    RepositorioRfq::guardar_total_price_total_cost_fedbid(Conexion::obtener_conexion(), $_POST['total_cost_fedbid'], $_POST['total_price_fedbid'], $_POST['id_rfq']);
  }
  if($cotizacion_recuperada-> obtener_canal() == 'Chemonics' || $cotizacion_recuperada-> obtener_canal() == 'Ebay & Amazon'){
    RepositorioRfq::guardar_total_price_chemonics(Conexion::obtener_conexion(), $_POST['total_price_chemonics'], $_POST['id_rfq']);
  }
  /*****************************************************************************************************************************/
  /*****************************************************************************************************************************/
  $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $canal = Input::translate_channel($cotizacion_recuperada->obtener_canal());
  if($cotizacion_recuperada-> obtener_canal() == 'Chemonics' || $cotizacion_recuperada-> obtener_canal() == 'Ebay & Amazon'){
    if(isset($_POST['award']) && $_POST['award'] == 'si'){
      RepositorioRfq::check_completed(Conexion::obtener_conexion(), $_POST['id_rfq']);
      RepositorioRfq::actualizar_fecha_y_submitted(Conexion::obtener_conexion(), $_POST['id_rfq']);
      RepositorioRfq::actualizar_fecha_y_award(Conexion::obtener_conexion(), $_POST['id_rfq']);
      Redireccion::redirigir(AWARD . $canal);
    }
  }else{
    if (!$cotizacion_recuperada->obtener_completado()) {
      if (isset($_POST['completado']) && $_POST['completado'] == 'si') {
        RepositorioRfq::check_completed(Conexion::obtener_conexion(), $_POST['id_rfq']);
        $descripcion = 'The quote was completed.';
        $comment = new Comment('', $cotizacion_recuperada-> obtener_id(), $_SESSION['id_usuario'], $descripcion, '');
        RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
        Redireccion::redirigir(COMPLETADOS . $canal);
      }
    } else if (!$cotizacion_recuperada->obtener_status()) {
      if (isset($_POST['status']) && $_POST['status'] == 'si') {
        $descripcion = 'The quote was submitted.';
        $comment = new Comment('', $cotizacion_recuperada-> obtener_id(), $_SESSION['id_usuario'], $descripcion, '');
        RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
        RepositorioRfq::actualizar_fecha_y_submitted(Conexion::obtener_conexion(), $_POST['id_rfq']);
        Redireccion::redirigir(COMPLETADOS . $canal);
      }
    }else if(!$cotizacion_recuperada-> obtener_award()){
      if(isset($_POST['award']) && $_POST['award'] == 'si'){
        RepositorioRfq::actualizar_fecha_y_award(Conexion::obtener_conexion(), $_POST['id_rfq']);
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_usuario_designado());
        Email::send_email_quote_awarded($usuario-> obtener_email(), $cotizacion_recuperada-> obtener_id(), nl2br($_POST['address']));
        $descripcion = 'The quote was awarded.';
        $comment = new Comment('', $cotizacion_recuperada-> obtener_id(), $_SESSION['id_usuario'], $descripcion, '');
        RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
        Redireccion::redirigir(AWARD . $canal);
      }
    }
  }
  AuditTrailRepository::items_table_events(Conexion::obtener_conexion(), $_POST['taxes'], $_POST['taxes_original'], $_POST['profit'], $_POST['profit_original'], $_POST['additional_general'], $_POST['additional_general_original'], $_POST['payment_terms'], $_POST['payment_terms_original'], $_POST['shipping'], $_POST['shipping_original'], $_POST['shipping_cost'], $_POST['shipping_cost_original'], $_POST['id_rfq']);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
}
?>
