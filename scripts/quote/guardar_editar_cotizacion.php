<?php
if (isset($_POST['guardar_cambios_cotizacion'])) {
  // Sanitize and validate inputs
  $id_rfq = filter_input(INPUT_POST, 'id_rfq', FILTER_VALIDATE_INT);
  $id_items = filter_input(INPUT_POST, 'id_items', FILTER_SANITIZE_SPECIAL_CHARS);
  $id_subitems = filter_input(INPUT_POST, 'id_subitems', FILTER_SANITIZE_SPECIAL_CHARS);
  $partes_total_price = filter_input(INPUT_POST, 'partes_total_price', FILTER_SANITIZE_SPECIAL_CHARS);
  $partes_total_price_subitems = filter_input(INPUT_POST, 'partes_total_price_subitems', FILTER_SANITIZE_SPECIAL_CHARS);
  $unit_prices = filter_input(INPUT_POST, 'unit_prices', FILTER_SANITIZE_SPECIAL_CHARS);
  $unit_prices_subitems = filter_input(INPUT_POST, 'unit_prices_subitems', FILTER_SANITIZE_SPECIAL_CHARS);
  $additional = filter_input(INPUT_POST, 'additional', FILTER_SANITIZE_SPECIAL_CHARS);
  $additional_subitems = filter_input(INPUT_POST, 'additional_subitems', FILTER_SANITIZE_SPECIAL_CHARS);
  $payment_terms = filter_input(INPUT_POST, 'payment_terms', FILTER_SANITIZE_SPECIAL_CHARS);
  $taxes = filter_input(INPUT_POST, 'taxes', FILTER_VALIDATE_FLOAT);
  $profit = filter_input(INPUT_POST, 'profit', FILTER_VALIDATE_FLOAT);
  $total_cost = filter_input(INPUT_POST, 'total_cost', FILTER_VALIDATE_FLOAT);
  $total_price = filter_input(INPUT_POST, 'total_price', FILTER_VALIDATE_FLOAT);
  $additional_general = filter_input(INPUT_POST, 'additional_general', FILTER_SANITIZE_SPECIAL_CHARS);
  $shipping = filter_input(INPUT_POST, 'shipping', FILTER_SANITIZE_SPECIAL_CHARS);
  $shipping_cost = filter_input(INPUT_POST, 'shipping_cost', FILTER_VALIDATE_FLOAT);
  $award = filter_input(INPUT_POST, 'award', FILTER_SANITIZE_SPECIAL_CHARS);
  $completado = filter_input(INPUT_POST, 'completado', FILTER_SANITIZE_SPECIAL_CHARS);
  $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
  $fulfillment = filter_input(INPUT_POST, 'fulfillment', FILTER_SANITIZE_SPECIAL_CHARS);
  $invoice = filter_input(INPUT_POST, 'invoice', FILTER_SANITIZE_SPECIAL_CHARS);
  $submitted_invoice = filter_input(INPUT_POST, 'submitted_invoice', FILTER_SANITIZE_SPECIAL_CHARS);
  $type_of_contract = filter_input(INPUT_POST, 'type_of_contract', FILTER_SANITIZE_SPECIAL_CHARS);
  $services_payment_term = filter_input(INPUT_POST, 'services_payment_term', FILTER_SANITIZE_SPECIAL_CHARS);
  $invoice_date = filter_input(INPUT_POST, 'invoice_date', FILTER_SANITIZE_SPECIAL_CHARS);
  $sales_commission = filter_input(INPUT_POST, 'sales_commission', FILTER_VALIDATE_FLOAT);
  $sales_commission_comment = filter_input(INPUT_POST, 'sales_commission_comment', FILTER_SANITIZE_SPECIAL_CHARS);

  // Check required fields
  if ($id_rfq === false || $taxes === false || $profit === false || $total_cost === false || $total_price === false || $shipping_cost === false) {
    echo 'Error: Invalid input data.';
    exit;
  }

  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);

    RepositorioRfq::insert_calc(
      $conexion,
      $id_items,
      $id_subitems,
      $partes_total_price,
      $partes_total_price_subitems,
      $unit_prices,
      $unit_prices_subitems,
      $additional,
      $additional_subitems
    );

    RepositorioRfq::update_variables(
      $conexion,
      $payment_terms,
      $taxes,
      $profit,
      $total_cost,
      $total_price,
      $additional_general,
      htmlspecialchars($shipping),
      $shipping_cost,
      $id_rfq
    );

    if ($cotizacion_recuperada->obtener_canal() == 'Chemonics') {
      RepositorioRfq::guardar_total_price_chemonics($conexion, $_POST['total_price_chemonics'], $id_rfq);
    }

    $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);

    AuditTrailRepository::items_table_events(
      $conexion,
      $taxes,
      $_POST['taxes_original'] ?? '',
      $profit,
      $_POST['profit_original'] ?? '',
      $additional_general,
      $_POST['additional_general_original'] ?? '',
      $payment_terms,
      $_POST['payment_terms_original'] ?? '',
      $shipping,
      $_POST['shipping_original'] ?? '',
      $shipping_cost,
      $_POST['shipping_cost_original'] ?? '',
      $id_rfq
    );

    ServiceRepository::calc_items_with_CC($conexion, $services_payment_term, $id_rfq);

    Conexion::cerrar_conexion();

    if ($cotizacion_recuperada->obtener_canal() == 'Chemonics' && $award === 'si') {
      Conexion::abrir_conexion();
      $conexion = Conexion::obtener_conexion();

      RepositorioRfq::check_completed($conexion, $id_rfq);
      RepositorioRfq::actualizar_fecha_y_submitted($conexion, $id_rfq);
      RepositorioRfq::actualizar_fecha_y_award($conexion, $id_rfq);

      Conexion::cerrar_conexion();
      Redireccion::redirigir(AWARD . $cotizacion_recuperada->obtener_canal());
    } else {
      if (!$cotizacion_recuperada->obtener_completado() && $completado === 'si') {
        Conexion::abrir_conexion();
        $conexion = Conexion::obtener_conexion();

        RepositorioRfq::check_completed($conexion, $id_rfq);
        AuditTrailRepository::quote_status_audit_trail($conexion, 'Completed', $id_rfq);

        Conexion::cerrar_conexion();
        Redireccion::redirigir(COMPLETED . $cotizacion_recuperada->obtener_canal());
      } elseif (!$cotizacion_recuperada->obtener_status() && $status === 'si') {
        Conexion::abrir_conexion();
        $conexion = Conexion::obtener_conexion();

        AuditTrailRepository::quote_status_audit_trail($conexion, 'Submitted', $id_rfq);
        RepositorioRfq::actualizar_fecha_y_submitted($conexion, $id_rfq);

        Conexion::cerrar_conexion();
        Redireccion::redirigir(COMPLETED . $cotizacion_recuperada->obtener_canal());
      } elseif (!$cotizacion_recuperada->obtener_award() && $award === 'si') {
        Conexion::abrir_conexion();
        $conexion = Conexion::obtener_conexion();

        RepositorioRfq::actualizar_fecha_y_award($conexion, $id_rfq);
        $usuario = RepositorioUsuario::obtener_usuario_por_id($conexion, $cotizacion_recuperada->obtener_usuario_designado());
        TeamsIntegration::notifyQuoteAward($cotizacion_recuperada->obtener_id(), $usuario);
        AuditTrailRepository::quote_status_audit_trail($conexion, 'Awarded', $id_rfq);

        Conexion::cerrar_conexion();
        Redireccion::redirigir(AWARD . $cotizacion_recuperada->obtener_canal());
      } elseif (!$cotizacion_recuperada->obtener_fullfillment() && $fulfillment === 'si') {
        Conexion::abrir_conexion();
        $conexion = Conexion::obtener_conexion();

        RepositorioRfq::check_fulfillment_and_date($conexion, $id_rfq);
        RepositorioRfq::set_type_of_contract($conexion, $type_of_contract, $id_rfq);
        $fulfillment_users = RepositorioUsuario::get_fulfillment_users($conexion);
        TeamsIntegration::notifyQuoteFulfillment($id_rfq, $type_of_contract, $fulfillment_users);
        AuditTrailRepository::quote_status_audit_trail($conexion, 'Fulfillment', $id_rfq);

        Conexion::cerrar_conexion();
        Redireccion::redirigir(FULFILLMENT_QUOTES);
      } elseif (!$cotizacion_recuperada->obtener_invoice() && $invoice === 'si') {
        Conexion::abrir_conexion();
        $conexion = Conexion::obtener_conexion();

        RepositorioRfq::check_invoice($conexion, $id_rfq);
        RepositorioRfq::set_sales_commission($conexion, $invoice_date, $sales_commission, $sales_commission_comment, $id_rfq);
        $accounting_users = RepositorioUsuario::get_accounting_users($conexion);
        Email::send_email_invoice_quote($accounting_users, $cotizacion_recuperada);
        AuditTrailRepository::quote_status_audit_trail($conexion, 'Invoice', $id_rfq);

        Conexion::cerrar_conexion();
        Redireccion::redirigir(INVOICE_QUOTES);
      } elseif (!$cotizacion_recuperada->obtener_submitted_invoice() && $submitted_invoice === 'si') {
        Conexion::abrir_conexion();
        $conexion = Conexion::obtener_conexion();

        RepositorioRfq::check_submitted_invoice_and_date($conexion, $id_rfq);
        AuditTrailRepository::quote_status_audit_trail($conexion, 'Submitted Invoice', $id_rfq);

        Conexion::cerrar_conexion();
        Redireccion::redirigir(SUBMITTED_INVOICE_QUOTES);
      }
    }

    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq);
  } catch (Exception $e) {
    if (isset($conexion)) {
      Conexion::cerrar_conexion();
    }
    echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
  }
}
