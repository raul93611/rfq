<?php
session_start();
if (isset($_POST['guardar_cambios_cotizacion'])) {
  Conexion::abrir_conexion();
  $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  RepositorioRfq::insert_calc(Conexion::obtener_conexion(), $_POST['id_items'], $_POST['id_subitems'], $_POST['partes_total_price'], $_POST['partes_total_price_subitems'], $_POST['unit_prices'], $_POST['unit_prices_subitems'], $_POST['additional'], $_POST['additional_subitems']);
  RepositorioRfq::update_variables(Conexion::obtener_conexion(), $_POST['payment_terms'], $_POST['taxes'], $_POST['profit'], $_POST['total_cost'], $_POST['total_price'], $_POST['additional_general'], htmlspecialchars($_POST['shipping']), $_POST['shipping_cost'], $_POST['id_rfq']);
  if($cotizacion_recuperada-> obtener_canal() == 'FedBid'){
    RepositorioRfq::guardar_total_price_total_cost_fedbid(Conexion::obtener_conexion(), $_POST['total_cost_fedbid'], $_POST['total_price_fedbid'], $_POST['id_rfq']);
  }
  if($cotizacion_recuperada-> obtener_canal() == 'Chemonics' || $cotizacion_recuperada-> obtener_canal() == 'Ebay & Amazon'){
    RepositorioRfq::guardar_total_price_chemonics(Conexion::obtener_conexion(), $_POST['total_price_chemonics'], $_POST['id_rfq']);
  }
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
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Completed', $_POST['id_rfq']);
        Redireccion::redirigir(COMPLETADOS . $canal);
      }
    } else if (!$cotizacion_recuperada->obtener_status()) {
      if (isset($_POST['status']) && $_POST['status'] == 'si') {
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Submitted', $_POST['id_rfq']);
        RepositorioRfq::actualizar_fecha_y_submitted(Conexion::obtener_conexion(), $_POST['id_rfq']);
        Redireccion::redirigir(COMPLETADOS . $canal);
      }
    }else if(!$cotizacion_recuperada-> obtener_award()){
      if(isset($_POST['award']) && $_POST['award'] == 'si'){
        RepositorioRfq::actualizar_fecha_y_award(Conexion::obtener_conexion(), $_POST['id_rfq']);
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_usuario_designado());
        Email::send_email_quote_awarded($usuario-> obtener_email(), $cotizacion_recuperada-> obtener_id(), nl2br($_POST['address']));
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Awarded', $_POST['id_rfq']);
        Redireccion::redirigir(AWARD . $canal);
      }
    }else if(!$cotizacion_recuperada-> obtener_fullfillment()){
      if(isset($_POST['fulfillment']) && $_POST['fulfillment'] == 'si'){
        if(isset($_POST['multi_year_project']) && $_POST['multi_year_project'] == '1'){
          $multi_year_project = 1;
        }else{
          $multi_year_project = 0;
        }
        RepositorioRfq::check_fulfillment_and_date(Conexion::obtener_conexion(), $_POST['total_price_confirmation'], $multi_year_project, $_POST['id_rfq']);
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Fulfillment', $_POST['id_rfq']);
        Redireccion::redirigir(FULFILLMENT_QUOTES);
      }
    }else if(!$cotizacion_recuperada-> obtener_invoice()){
      if(isset($_POST['invoice']) && $_POST['invoice'] == 'si'){
        RepositorioRfq::check_invoice_and_date(Conexion::obtener_conexion(), $_POST['id_rfq']);
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Invoice', $_POST['id_rfq']);
        Redireccion::redirigir(INVOICE_QUOTES);
      }
    }else if(!$cotizacion_recuperada-> obtener_submitted_invoice()){
      if(isset($_POST['submitted_invoice']) && $_POST['submitted_invoice'] == 'si'){
        RepositorioRfq::check_submitted_invoice_and_date(Conexion::obtener_conexion(), $_POST['id_rfq']);
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Submitted Invoice', $_POST['id_rfq']);
        Redireccion::redirigir(SUBMITTED_INVOICE_QUOTES);
      }
    }
  }
  AuditTrailRepository::items_table_events(Conexion::obtener_conexion(), $_POST['taxes'], $_POST['taxes_original'], $_POST['profit'], $_POST['profit_original'], $_POST['additional_general'], $_POST['additional_general_original'], $_POST['payment_terms'], $_POST['payment_terms_original'], $_POST['shipping'], $_POST['shipping_original'], $_POST['shipping_cost'], $_POST['shipping_cost_original'], $_POST['id_rfq']);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
}
?>
