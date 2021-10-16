<?php
define('NOMBRE_SERVIDOR', 'localhost');
define('NOMBRE_USUARIO', 'root');
define('PASSWORD', '');
define('NOMBRE_BD', 'elogic');

define('SERVIDOR', 'http://localhost/rfq/');
define('ERROR', SERVIDOR . 'error');
/*****************************USER OPTIONS************************************/
define('PERFIL', SERVIDOR . 'perfil/');
define('LOGOUT', SERVIDOR . 'logout');
define('REGISTRO', PERFIL . 'registro');
define('EDIT_USER', PERFIL . 'edit_user/');
define('DISABLE_USER', SERVIDOR . 'disable_user/');
define('ENABLE_USER', SERVIDOR . 'enable_user/');
/********************************QUOTES*****************************************/
define('SEARCH_QUOTES', PERFIL . 'search_quotes');
define('COTIZACIONES', PERFIL . 'cotizaciones/');
define('GSA_BUY', COTIZACIONES . 'gsa_buy');
define('FEDBID', COTIZACIONES . 'fedbid');
define('EMAILS', COTIZACIONES . 'emails');
define('MAILBOX', COTIZACIONES . 'mailbox');
define('FINDFRP', COTIZACIONES . 'findfrp');
define('EMBASSIES', COTIZACIONES . 'embassies');
define('FBO', COTIZACIONES . 'fbo');
define('CHEMONICS', COTIZACIONES . 'chemonics');
define('EBAY_AMAZON', COTIZACIONES . 'ebay_amazon');
define('STARSIII', COTIZACIONES . 'starsiii');
/*********************************COMPLETED*************************************/
define('COMPLETADOS', PERFIL . 'completados/');
define('GSA_BUY_COMPLETADOS', COMPLETADOS . 'gsa_buy');
define('FEDBID_COMPLETADOS', COMPLETADOS . 'fedbid');
define('EMAILS_COMPLETADOS', COMPLETADOS . 'emails');
define('MAILBOX_COMPLETADOS', COMPLETADOS . 'mailbox');
define('FINDFRP_COMPLETADOS', COMPLETADOS . 'findfrp');
define('EMBASSIES_COMPLETADOS', COMPLETADOS . 'embassies');
define('FBO_COMPLETADOS', COMPLETADOS . 'fbo');
define('STARSIII_COMPLETADOS', COMPLETADOS . 'starsiii');
/*******************************SUBMITTED***************************************/
define('SUBMITTED', PERFIL . 'submitted/');
define('GSA_BUY_SUBMITTED', SUBMITTED . 'gsa_buy');
define('FEDBID_SUBMITTED', SUBMITTED . 'fedbid');
define('EMAILS_SUBMITTED', SUBMITTED . 'emails');
define('MAILBOX_SUBMITTED', SUBMITTED . 'mailbox');
define('FINDFRP_SUBMITTED', SUBMITTED . 'findfrp');
define('EMBASSIES_SUBMITTED', SUBMITTED . 'embassies');
define('FBO_SUBMITTED', SUBMITTED . 'fbo');
define('STARSIII_SUBMITTED', SUBMITTED . 'starsiii');
/****************************AWARD**********************************************/
define('AWARD', PERFIL . 'award/');
define('GSA_BUY_AWARD', AWARD . 'gsa_buy');
define('FEDBID_AWARD', AWARD . 'fedbid');
define('EMAILS_AWARD', AWARD . 'emails');
define('MAILBOX_AWARD', AWARD . 'mailbox');
define('FINDFRP_AWARD', AWARD . 'findfrp');
define('EMBASSIES_AWARD', AWARD . 'embassies');
define('FBO_AWARD', AWARD . 'fbo');
define('CHEMONICS_AWARD', AWARD . 'chemonics');
define('EBAY_AMAZON_AWARD', AWARD . 'ebay_amazon');
define('STARSIII_AWARD', AWARD . 'starsiii');
/***************************QUOTES OPTIONS**************************************/
define('COPY_QUOTE', SERVIDOR . 'copy_quote/');
define('NUEVA_COTIZACION', COTIZACIONES . 'nuevo');
define('EDITAR_COTIZACION', COTIZACIONES . 'editar_cotizacion');
define('DELETE_QUOTE', SERVIDOR . 'delete_quote');
define('GUARDAR_EDITAR_COTIZACION', SERVIDOR . 'guardar_editar_cotizacion/');
define('SAVE_QUOTE_INFO', SERVIDOR . 'save_quote_info');
/***************************OTHER SECTIONS**************************************/
define('NO_BID', PERFIL . 'no_bid');
define('NO_SUBMITTED', PERFIL . 'no_submitted');
define('CANCELLED', PERFIL . 'cancelled');
/***************************PROPOSAL********************************************/
define('PROPOSAL', SERVIDOR . 'proposal');
define('PROPOSAL_GSA', SERVIDOR . 'proposal_gsa');
/*************************ITEMS OPTIONS*****************************************/
define('ADD_ITEM', COTIZACIONES . 'add_item');
define('DELETE_ITEM', SERVIDOR . 'delete_item');
define('EDIT_ITEM', COTIZACIONES . 'edit_item');
define('GUARDAR_ADD_ITEM', SERVIDOR . 'guardar_add_item/');
define('GUARDAR_EDIT_ITEM', SERVIDOR . 'guardar_edit_item/');
/*************************SUBITEMS OPTIONS***************************************/
define('DELETE_SUBITEM', SERVIDOR . 'delete_subitem');
define('ADD_SUBITEM', COTIZACIONES . 'add_subitem');
define('EDIT_SUBITEM', COTIZACIONES . 'edit_subitem');
define('GUARDAR_ADD_SUBITEM', SERVIDOR . 'guardar_add_subitem/');
define('GUARDAR_EDIT_SUBITEM', SERVIDOR . 'guardar_edit_subitem/');
/**************************PROVIDER OPTIONS*************************************/
define('ADD_PROVIDER', COTIZACIONES . 'add_provider');
define('EDIT_PROVIDER', COTIZACIONES . 'edit_provider');
define('DELETE_PROVIDER', SERVIDOR . 'delete_provider');
define('GUARDAR_ADD_PROVIDER', SERVIDOR . 'guardar_add_provider/');
define('GUARDAR_EDIT_PROVIDER', SERVIDOR . 'guardar_edit_provider/');
/***************************PROVIDER SUBITEMS OPTIONS**************************/
define('ADD_PROVIDER_SUBITEM', COTIZACIONES . 'add_provider_subitem');
define('EDIT_PROVIDER_SUBITEM', COTIZACIONES . 'edit_provider_subitem');
define('DELETE_PROVIDER_SUBITEM', SERVIDOR . 'delete_provider_subitem');
define('GUARDAR_ADD_PROVIDER_SUBITEM', SERVIDOR . 'guardar_add_provider_subitem/');
define('GUARDAR_EDIT_PROVIDER_SUBITEM', SERVIDOR . 'guardar_edit_provider_subitem/');
/**************************************************************************************/
define('PDF_REPORT', SERVIDOR . 'pdf_report');
/************************************************************************************/
define('GUARDAR_COMMENT', SERVIDOR . 'guardar_comment/');
/***********************************************************************************/
define('GUARDAR_FULLFILLMENT_FORM', SERVIDOR . 'guardar_fullfillment_form');
/**********************************************************************************/
define('PDF_TABLA_ITEMS', SERVIDOR . 'pdf_tabla_items/');
define('PDF_RE_QUOTE', SERVIDOR . 'pdf_re_quote/');
define('EXCEL_ITEMS_TABLE', SERVIDOR . 'excel_items_table/');
/**********************************************************************************/
define('REPORTS', PERFIL . 'reports');
/*************************************************************************************/
define('EMPLOYEE_DOCS_PAGE', PERFIL . 'employee_docs_page/');
/********************************************************************************/
define('DELETE_DOCUMENT', SERVIDOR . 'delete_document');
/********************************************************************************/
define('RECOVER_PASSWORD_FORM', SERVIDOR . 'recover_password_form');
define('RESTART_PASSWORD', SERVIDOR . 'restart_password/');
/***************************************************************************************/
define('RE_QUOTE', PERFIL . 're_quote/');
define('RELOAD_REQUOTE', SERVIDOR . 'reload_requote/');
define('SAVE_RE_QUOTE', SERVIDOR . 'save_re_quote');
define('ADD_RE_QUOTE_ITEM', PERFIL . 'add_re_quote_item/');
define('SAVE_RE_QUOTE_ITEM', SERVIDOR . 'save_re_quote_item');
define('EDIT_RE_QUOTE_ITEM', PERFIL . 'edit_re_quote_item/');
define('SAVE_EDIT_RE_QUOTE_ITEM', SERVIDOR . 'save_edit_re_quote_item');
define('DELETE_RE_QUOTE_ITEM', SERVIDOR . 'delete_re_quote_item/');
define('ADD_RE_QUOTE_PROVIDER', PERFIL . 'add_re_quote_provider/');
define('SAVE_RE_QUOTE_PROVIDER', SERVIDOR . 'save_re_quote_provider');
define('EDIT_RE_QUOTE_PROVIDER', PERFIL . 'edit_re_quote_provider/');
define('SAVE_EDIT_RE_QUOTE_PROVIDER', SERVIDOR . 'save_edit_re_quote_provider');
define('DELETE_RE_QUOTE_PROVIDER', SERVIDOR . 'delete_re_quote_provider/');
define('ADD_RE_QUOTE_SUBITEM', PERFIL . 'add_re_quote_subitem/');
define('SAVE_RE_QUOTE_SUBITEM', SERVIDOR . 'save_re_quote_subitem/');
define('EDIT_RE_QUOTE_SUBITEM', PERFIL . 'edit_re_quote_subitem/');
define('SAVE_EDIT_RE_QUOTE_SUBITEM', SERVIDOR . 'save_edit_re_quote_subitem');
define('DELETE_RE_QUOTE_SUBITEM', SERVIDOR . 'delete_re_quote_subitem/');
define('ADD_RE_QUOTE_SUBITEM_PROVIDER', PERFIL . 'add_re_quote_subitem_provider/');
define('SAVE_RE_QUOTE_SUBITEM_PROVIDER', SERVIDOR . 'save_re_quote_subitem_provider');
define('EDIT_RE_QUOTE_SUBITEM_PROVIDER', PERFIL . 'edit_re_quote_subitem_provider/');
define('SAVE_EDIT_RE_QUOTE_SUBITEM_PROVIDER', SERVIDOR . 'save_edit_re_quote_subitem_provider');
define('DELETE_RE_QUOTE_SUBITEM_PROVIDER', SERVIDOR . 'delete_re_quote_subitem_provider/');
/***********************************************************************************/
define('FULFILLMENT_QUOTES', PERFIL . 'fulfillment_quotes');
define('INVOICE_QUOTES', PERFIL . 'invoice_quotes');
define('SUBMITTED_INVOICE_QUOTES', PERFIL . 'submitted_invoice_quotes');
/******************************************************************************/
define('REMOVE_AWARD', SERVIDOR . 'remove_award/');
define('REMOVE_FULFILLMENT', SERVIDOR . 'remove_fulfillment/');
define('REMOVE_SLAVE', SERVIDOR . 'remove_slave/');
define('REMOVE_MASTER', SERVIDOR . 'remove_master/');
/******************************************************************************/
define('ADD_SERVICE', SERVIDOR . 'add_service');
define('LOAD_SERVICE', SERVIDOR . 'load_service/');
define('EDIT_SERVICE', SERVIDOR . 'edit_service');
define('DELETE_SERVICE', SERVIDOR . 'delete_service/');
/******************************************************************************/
define('TRACKING', PERFIL . 'tracking/');
define('SAVE_TRACKING', SERVIDOR . 'save_tracking');
define('SAVE_TRACKING_SUBITEM', SERVIDOR . 'save_tracking_subitem');
define('DELETE_TRACKING', SERVIDOR . 'delete_tracking/');
define('DELETE_TRACKING_SUBITEM', SERVIDOR . 'delete_tracking_subitem/');
define('TRACKING_PDF', SERVIDOR . 'tracking_pdf/');
/******************************************************************************/
define('FULFILLMENT', PERFIL . 'fulfillment/');
define('SAVE_FULFILLMENT_ITEM', SERVIDOR . 'save_fulfillment_item');
define('SAVE_FULFILLMENT_SUBITEM', SERVIDOR . 'save_fulfillment_subitem');
define('DELETE_FULFILLMENT_ITEM', SERVIDOR . 'delete_fulfillment_item/');
define('DELETE_FULFILLMENT_SUBITEM', SERVIDOR . 'delete_fulfillment_subitem/');
/******************************************************************************/
define('SAVE_FULFILLMENT_SERVICE', SERVIDOR . 'save_fulfillment_service');
/******************************************************************************/
define('MARK_AS_PENDING', SERVIDOR . 'mark_as_pending/');
define('UNMARK_AS_PENDING', SERVIDOR . 'unmark_as_pending/');
/******************************************************************************/
define('PROVIDERS', PERFIL . 'providers');
define('PAYMENT_TERMS', PERFIL . 'payment_terms');
/******************************************************************************/
define('CHARTS', PERFIL . 'charts');
/******************************************************************************/
define('MY_TASKS', PERFIL . 'my_tasks');
define('TASKS_DONE', PERFIL . 'tasks_done');
/******************************************************************************/
define('EMPLOYEE_DOCS', SERVIDOR . 'employee_docs/');
define('REPORTS_DOCS', SERVIDOR . 'reports/');
define('DOCS', SERVIDOR . 'documentos/');
define('RUTA_CSS', SERVIDOR . 'css/');
define('RUTA_JS', SERVIDOR . 'js/');
define('RUTA_IMG', SERVIDOR . 'img/');
define('PLUGINS', SERVIDOR . 'plugins/');
define('DIST', SERVIDOR . 'dist/');
?>
