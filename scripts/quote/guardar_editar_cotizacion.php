<?php
if (isset($_POST['guardar_cambios_cotizacion'])) {
  Conexion::abrir_conexion();
  $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  RepositorioRfq::insert_calc(Conexion::obtener_conexion(), $_POST['id_items'], $_POST['id_subitems'], $_POST['partes_total_price'], $_POST['partes_total_price_subitems'], $_POST['unit_prices'], $_POST['unit_prices_subitems'], $_POST['additional'], $_POST['additional_subitems']);
  RepositorioRfq::update_variables(Conexion::obtener_conexion(), $_POST['payment_terms'], $_POST['taxes'], $_POST['profit'], $_POST['total_cost'], $_POST['total_price'], $_POST['additional_general'], htmlspecialchars($_POST['shipping']), $_POST['shipping_cost'], $_POST['id_rfq']);
  if($cotizacion_recuperada-> obtener_canal() == 'Chemonics'){
    RepositorioRfq::guardar_total_price_chemonics(Conexion::obtener_conexion(), $_POST['total_price_chemonics'], $_POST['id_rfq']);
  }
  $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  AuditTrailRepository::items_table_events(Conexion::obtener_conexion(), $_POST['taxes'], $_POST['taxes_original'], $_POST['profit'], $_POST['profit_original'], $_POST['additional_general'], $_POST['additional_general_original'], $_POST['payment_terms'], $_POST['payment_terms_original'], $_POST['shipping'], $_POST['shipping_original'], $_POST['shipping_cost'], $_POST['shipping_cost_original'], $_POST['id_rfq']);
  ServiceRepository::calc_items_with_CC(Conexion::obtener_conexion(), $_POST['services_payment_term'] ?? null, $_POST['id_rfq']);
  Conexion::cerrar_conexion();
  if($cotizacion_recuperada-> obtener_canal() == 'Chemonics'){
    if(isset($_POST['award']) && $_POST['award'] == 'si'){
      Conexion::abrir_conexion();
      RepositorioRfq::check_completed(Conexion::obtener_conexion(), $_POST['id_rfq']);
      RepositorioRfq::actualizar_fecha_y_submitted(Conexion::obtener_conexion(), $_POST['id_rfq']);
      RepositorioRfq::actualizar_fecha_y_award(Conexion::obtener_conexion(), $_POST['id_rfq']);
      Conexion::cerrar_conexion();
      Redireccion::redirigir(AWARD . $cotizacion_recuperada->obtener_canal());
    }
  }else{
    if (!$cotizacion_recuperada->obtener_completado()) {
      if (isset($_POST['completado']) && $_POST['completado'] == 'si') {
        Conexion::abrir_conexion();
        RepositorioRfq::check_completed(Conexion::obtener_conexion(), $_POST['id_rfq']);
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Completed', $_POST['id_rfq']);
        Conexion::cerrar_conexion();
        Redireccion::redirigir(COMPLETED . $cotizacion_recuperada->obtener_canal());
      }
    } else if (!$cotizacion_recuperada->obtener_status()) {
      if (isset($_POST['status']) && $_POST['status'] == 'si') {
        Conexion::abrir_conexion();
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Submitted', $_POST['id_rfq']);
        RepositorioRfq::actualizar_fecha_y_submitted(Conexion::obtener_conexion(), $_POST['id_rfq']);
        Conexion::cerrar_conexion();
        Redireccion::redirigir(COMPLETED . $cotizacion_recuperada->obtener_canal());
      }
    }else if(!$cotizacion_recuperada-> obtener_award()){
      if(isset($_POST['award']) && $_POST['award'] == 'si'){
        Conexion::abrir_conexion();
        RepositorioRfq::actualizar_fecha_y_award(Conexion::obtener_conexion(), $_POST['id_rfq']);
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_usuario_designado());
        TeamsIntegration::notifyQuoteAward($cotizacion_recuperada-> obtener_id(), $usuario);        
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Awarded', $_POST['id_rfq']);
        Conexion::cerrar_conexion();
        Redireccion::redirigir(AWARD . $cotizacion_recuperada->obtener_canal());
      }
    }else if(!$cotizacion_recuperada-> obtener_fullfillment()){
      if(isset($_POST['fulfillment']) && $_POST['fulfillment'] == 'si'){
        Conexion::abrir_conexion();
        RepositorioRfq::check_fulfillment_and_date(Conexion::obtener_conexion(), $_POST['id_rfq']);
        RepositorioRfq::set_type_of_contract(Conexion::obtener_conexion(), $_POST['type_of_contract'], $_POST['id_rfq']);
        $fulfillment_users = RepositorioUsuario::get_fulfillment_users(Conexion::obtener_conexion());
        TeamsIntegration::notifyQuoteFulfillment($_POST['id_rfq'], $_POST['type_of_contract'], $fulfillment_users);
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Fulfillment', $_POST['id_rfq']);
        Conexion::cerrar_conexion();
        Redireccion::redirigir(FULFILLMENT_QUOTES);
      }
    }else if(!$cotizacion_recuperada-> obtener_invoice()){
      if(isset($_POST['invoice']) && $_POST['invoice'] == 'si'){
        Conexion::abrir_conexion();
        RepositorioRfq::check_invoice_and_date(Conexion::obtener_conexion(), $_POST['id_rfq']);
        RepositorioRfq::set_sales_commission(Conexion::obtener_conexion(), $_POST['sales_commission'], $_POST['sales_commission_comment'], $_POST['id_rfq']);
        $accounting_users = RepositorioUsuario::get_accounting_users(Conexion::obtener_conexion());
        Email::send_email_invoice_quote($accounting_users, $cotizacion_recuperada);
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Invoice', $_POST['id_rfq']);
        Conexion::cerrar_conexion();
        Redireccion::redirigir(INVOICE_QUOTES);
      }
    }else if(!$cotizacion_recuperada-> obtener_submitted_invoice()){
      if(isset($_POST['submitted_invoice']) && $_POST['submitted_invoice'] == 'si'){
        Conexion::abrir_conexion();
        RepositorioRfq::check_submitted_invoice_and_date(Conexion::obtener_conexion(), $_POST['id_rfq']);
        AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Submitted Invoice', $_POST['id_rfq']);
        Conexion::cerrar_conexion();
        Redireccion::redirigir(SUBMITTED_INVOICE_QUOTES);
      }
    }
  }
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
}
?>
