<?php
session_start();
if (isset($_POST['guardar_cambios_cotizacion'])) {
  Database::open_connection();
  $quote = QuoteRepository::get_by_id(Database::get_connection(), $_POST['id_quote']);
  QuoteRepository::insert_calc(Database::get_connection(), $_POST['id_items'], $_POST['id_subitems'], $_POST['total_price_parts'], $_POST['total_price_subitems_parts'], $_POST['unit_prices'], $_POST['unit_prices_subitems'], $_POST['additional'], $_POST['additional_subitems']);
  QuoteRepository::update_variables(Database::get_connection(), $_POST['payment_terms'], $_POST['taxes'], $_POST['profit'], $_POST['total_cost'], $_POST['total_price'], $_POST['additional_general'], htmlspecialchars($_POST['shipping']), $_POST['shipping_cost'], $_POST['id_quote']);
  if($quote-> get_channel() == 'FedBid'){
    QuoteRepository::update_total_fedbid(Database::get_connection(), $_POST['total_cost_fedbid'], $_POST['total_price_fedbid'], $_POST['id_quote']);
  }
  if($quote-> get_channel() == 'Chemonics' || $quote-> get_channel() == 'Ebay & Amazon'){
    QuoteRepository::update_total_chemonics(Database::get_connection(), $_POST['total_price_chemonics'], $_POST['id_quote']);
  }
  $quote = QuoteRepository::get_by_id(Database::get_connection(), $_POST['id_quote']);
  $channel = Input::translate_channel($quote->get_channel());
  if($quote-> get_channel() == 'Chemonics' || $quote-> get_channel() == 'Ebay & Amazon'){
    if(isset($_POST['award']) && $_POST['award'] == 'si'){
      QuoteRepository::update_complete_status(Database::get_connection(), $_POST['id_quote']);
      QuoteRepository::update_submitted_status_date(Database::get_connection(), $_POST['id_quote']);
      QuoteRepository::update_award_status_date(Database::get_connection(), $_POST['id_quote']);
      Redirection::redirect(AWARD . $channel);
    }
  }else{
    if (!$quote->get_complete()) {
      if (isset($_POST['complete']) && $_POST['complete'] == 'si') {
        QuoteRepository::update_complete_status(Database::get_connection(), $_POST['id_quote']);
        AuditTrailRepository::quote_status_audit_trail(Database::get_connection(), 'Completed', $_POST['id_quote']);
        Redirection::redirect(COMPLETE . $channel);
      }
    } else if (!$quote->get_submitted()) {
      if (isset($_POST['submitted']) && $_POST['submitted'] == 'si') {
        AuditTrailRepository::quote_status_audit_trail(Database::get_connection(), 'Submitted', $_POST['id_quote']);
        QuoteRepository::update_submitted_status_date(Database::get_connection(), $_POST['id_quote']);
        Redirection::redirect(COMPLETE . $channel);
      }
    }else if(!$quote-> get_award()){
      if(isset($_POST['award']) && $_POST['award'] == 'si'){
        QuoteRepository::update_award_status_date(Database::get_connection(), $_POST['id_quote']);
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote-> get_assigned_user());
        Email::send_email_quote_awarded($usuario-> obtener_email(), $quote-> get_id(), nl2br($_POST['address']));
        AuditTrailRepository::quote_status_audit_trail(Database::get_connection(), 'Awarded', $_POST['id_quote']);
        Redirection::redirect(AWARD . $channel);
      }
    }else if(!$quote-> get_fulfillment()){
      if(isset($_POST['fulfillment']) && $_POST['fulfillment'] == 'si'){
        QuoteRepository::update_fulfillment_status_date(Database::get_connection(), $_POST['id_quote']);
        AuditTrailRepository::quote_status_audit_trail(Database::get_connection(), 'Fulfillment', $_POST['id_quote']);
        Redirection::redirect(FULFILLMENT_QUOTES);
      }
    }
  }
  AuditTrailRepository::items_table_events(Database::get_connection(), $_POST['taxes'], $_POST['taxes_original'], $_POST['profit'], $_POST['profit_original'], $_POST['additional_general'], $_POST['additional_general_original'], $_POST['payment_terms'], $_POST['payment_terms_original'], $_POST['shipping'], $_POST['shipping_original'], $_POST['shipping_cost'], $_POST['shipping_cost_original'], $_POST['id_quote']);
  Database::close_connection();
  Redirection::redirect(EDIT_QUOTE . '/' . $_POST['id_quote']);
}
?>
