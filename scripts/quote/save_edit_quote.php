<?php
session_start();
if (isset($_POST['guardar_cambios_cotizacion'])) {
  Database::open_connection();
  $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Database::get_connection(), $_POST['id_rfq']);
  RepositorioRfq::insert_calc(Database::get_connection(), $_POST['id_items'], $_POST['id_subitems'], $_POST['partes_total_price'], $_POST['partes_total_price_subitems'], $_POST['unit_prices'], $_POST['unit_prices_subitems'], $_POST['additional'], $_POST['additional_subitems']);
  RepositorioRfq::update_variables(Database::get_connection(), $_POST['payment_terms'], $_POST['taxes'], $_POST['profit'], $_POST['total_cost'], $_POST['total_price'], $_POST['additional_general'], htmlspecialchars($_POST['shipping']), $_POST['shipping_cost'], $_POST['id_rfq']);
  if($cotizacion_recuperada-> obtener_canal() == 'FedBid'){
    RepositorioRfq::guardar_total_price_total_cost_fedbid(Database::get_connection(), $_POST['total_cost_fedbid'], $_POST['total_price_fedbid'], $_POST['id_rfq']);
  }
  if($cotizacion_recuperada-> obtener_canal() == 'Chemonics' || $cotizacion_recuperada-> obtener_canal() == 'Ebay & Amazon'){
    RepositorioRfq::guardar_total_price_chemonics(Database::get_connection(), $_POST['total_price_chemonics'], $_POST['id_rfq']);
  }
  $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Database::get_connection(), $_POST['id_rfq']);
  $canal = Input::translate_channel($cotizacion_recuperada->obtener_canal());
  if($cotizacion_recuperada-> obtener_canal() == 'Chemonics' || $cotizacion_recuperada-> obtener_canal() == 'Ebay & Amazon'){
    if(isset($_POST['award']) && $_POST['award'] == 'si'){
      RepositorioRfq::check_completed(Database::get_connection(), $_POST['id_rfq']);
      RepositorioRfq::actualizar_fecha_y_submitted(Database::get_connection(), $_POST['id_rfq']);
      RepositorioRfq::actualizar_fecha_y_award(Database::get_connection(), $_POST['id_rfq']);
      Redirection::redirect(AWARD . $canal);
    }
  }else{
    if (!$cotizacion_recuperada->obtener_completado()) {
      if (isset($_POST['completado']) && $_POST['completado'] == 'si') {
        RepositorioRfq::check_completed(Database::get_connection(), $_POST['id_rfq']);
        AuditTrailRepository::quote_status_audit_trail(Database::get_connection(), 'Completed', $_POST['id_rfq']);
        Redirection::redirect(COMPLETE . $canal);
      }
    } else if (!$cotizacion_recuperada->obtener_status()) {
      if (isset($_POST['status']) && $_POST['status'] == 'si') {
        AuditTrailRepository::quote_status_audit_trail(Database::get_connection(), 'Submitted', $_POST['id_rfq']);
        RepositorioRfq::actualizar_fecha_y_submitted(Database::get_connection(), $_POST['id_rfq']);
        Redirection::redirect(COMPLETE . $canal);
      }
    }else if(!$cotizacion_recuperada-> obtener_award()){
      if(isset($_POST['award']) && $_POST['award'] == 'si'){
        RepositorioRfq::actualizar_fecha_y_award(Database::get_connection(), $_POST['id_rfq']);
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $cotizacion_recuperada-> obtener_usuario_designado());
        Email::send_email_quote_awarded($usuario-> obtener_email(), $cotizacion_recuperada-> obtener_id(), nl2br($_POST['address']));
        AuditTrailRepository::quote_status_audit_trail(Database::get_connection(), 'Awarded', $_POST['id_rfq']);
        Redirection::redirect(AWARD . $canal);
      }
    }else if(!$cotizacion_recuperada-> obtener_fullfillment()){
      if(isset($_POST['fulfillment']) && $_POST['fulfillment'] == 'si'){
        RepositorioRfq::check_fulfillment_and_date(Database::get_connection(), $_POST['id_rfq']);
        AuditTrailRepository::quote_status_audit_trail(Database::get_connection(), 'Fulfillment', $_POST['id_rfq']);
        Redirection::redirect(FULFILLMENT_QUOTES);
      }
    }
  }
  AuditTrailRepository::items_table_events(Database::get_connection(), $_POST['taxes'], $_POST['taxes_original'], $_POST['profit'], $_POST['profit_original'], $_POST['additional_general'], $_POST['additional_general_original'], $_POST['payment_terms'], $_POST['payment_terms_original'], $_POST['shipping'], $_POST['shipping_original'], $_POST['shipping_cost'], $_POST['shipping_cost_original'], $_POST['id_rfq']);
  Database::close_connection();
  Redirection::redirect(EDIT_QUOTE . '/' . $_POST['id_rfq']);
}
?>
