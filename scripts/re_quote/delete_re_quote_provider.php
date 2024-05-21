<?php
Conexion::abrir_conexion();
$re_quote_provider = ReQuoteProviderRepository::get_re_quote_provider_by_id(Conexion::obtener_conexion(), $id_re_quote_provider);
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $re_quote_provider->get_id_re_quote_item());
$re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item->get_id_re_quote());
ReQuoteProviderRepository::delete_re_quote_provider(Conexion::obtener_conexion(), $id_re_quote_provider);
ReQuoteAuditTrailRepository::create_audit_trail_item_provider_deleted(Conexion::obtener_conexion(), $re_quote_provider->get_provider(), 'Provider', $re_quote_item->get_id(), $re_quote_item->get_id_re_quote());
Conexion::cerrar_conexion();
Redireccion::redirigir(RE_QUOTE . $re_quote->get_id_rfq() . '#item' . $re_quote_item->get_id());
