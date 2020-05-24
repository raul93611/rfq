<?php
define('NOMBRE_SERVIDOR', 'localhost');
define('NOMBRE_USUARIO', 'root');
define('PASSWORD', '');
define('NOMBRE_BD', 'elogic');

define('SERVER_NAME', 'localhost');
define('USER_NAME', 'root');
define('BD_NAME', 'rfp');

define('SERVERNAME_FULLFILLMENT', 'localhost');
define('BD_NAME_FULLFILLMENT', 'fullfillment');
define('USERNAME_FULLFILLMENT', 'root');
define('PASSWORD_FULLFILLMENT', '');

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
/*********************************COMPLETED*************************************/
define('COMPLETADOS', PERFIL . 'completados/');
define('GSA_BUY_COMPLETADOS', COMPLETADOS . 'gsa_buy');
define('FEDBID_COMPLETADOS', COMPLETADOS . 'fedbid');
define('EMAILS_COMPLETADOS', COMPLETADOS . 'emails');
define('MAILBOX_COMPLETADOS', COMPLETADOS . 'mailbox');
define('FINDFRP_COMPLETADOS', COMPLETADOS . 'findfrp');
define('EMBASSIES_COMPLETADOS', COMPLETADOS . 'embassies');
define('FBO_COMPLETADOS', COMPLETADOS . 'fbo');
/*******************************SUBMITTED***************************************/
define('SUBMITTED', PERFIL . 'submitted/');
define('GSA_BUY_SUBMITTED', SUBMITTED . 'gsa_buy');
define('FEDBID_SUBMITTED', SUBMITTED . 'fedbid');
define('EMAILS_SUBMITTED', SUBMITTED . 'emails');
define('MAILBOX_SUBMITTED', SUBMITTED . 'mailbox');
define('FINDFRP_SUBMITTED', SUBMITTED . 'findfrp');
define('EMBASSIES_SUBMITTED', SUBMITTED . 'embassies');
define('FBO_SUBMITTED', SUBMITTED . 'fbo');
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
/***************************QUOTES OPTIONS**************************************/
define('COPY_QUOTE', SERVIDOR . 'copy_quote/');
define('NUEVA_COTIZACION', COTIZACIONES . 'nuevo');
define('EDITAR_COTIZACION', COTIZACIONES . 'editar_cotizacion');
define('DELETE_QUOTE', SERVIDOR . 'delete_quote');
define('GUARDAR_EDITAR_COTIZACION', SERVIDOR . 'guardar_editar_cotizacion/');
define('SAVE_QUOTE_INFO', SERVIDOR . 'save_quote_info');
/***************************OTHER SECTIONS**************************************/
define('NO_BID', COTIZACIONES . 'no_bid');
define('NO_SUBMITTED', COTIZACIONES . 'no_submitted');
define('RFP_QUOTES', COTIZACIONES . 'rfp_quotes');
define('CANCELLED', COTIZACIONES . 'cancelled');
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
define('PDF_PROJECT_CHARTER', SERVIDOR . 'pdf_project_charter/');
/************************************************************************************/
define('GUARDAR_COMMENT', SERVIDOR . 'guardar_comment/');
/*************************************************************************************/
define('CREATE_PROJECT', SERVIDOR . 'create_project/');
/***********************************************************************************/
define('GUARDAR_FULLFILLMENT_FORM', SERVIDOR . 'guardar_fullfillment_form');
/**********************************************************************************/
define('PDF_TABLA_ITEMS', SERVIDOR . 'pdf_tabla_items/');
define('PDF_RE_QUOTE', SERVIDOR . 'pdf_re_quote/');
/**********************************************************************************/
define('EXCEL_REPORTS', PERFIL . 'excel_reports');
define('GENERATE_EXCEL_REPORT', SERVIDOR . 'generate_excel_report');
/*************************************************************************************/
define('EMPLOYEE_DOCS_PAGE', PERFIL . 'employee_docs_page/');
/********************************************************************************/
define('DELETE_DOCUMENT', SERVIDOR . 'delete_document');
/********************************************************************************/
define('RECOVER_PASSWORD_FORM', SERVIDOR . 'recover_password_form');
define('RESTART_PASSWORD', SERVIDOR . 'restart_password/');
/***************************************************************************************/
define('RE_QUOTE', PERFIL . 're_quote/');
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
/******************************************************************************/
define('REMOVE_AWARD', SERVIDOR . 'remove_award/');
define('REMOVE_FULFILLMENT', SERVIDOR . 'remove_fulfillment/');

define('EMPLOYEE_DOCS', SERVIDOR . 'employee_docs/');
define('REPORTS_DOCS', SERVIDOR . 'reports/');
define('DOCS', SERVIDOR . 'documentos/');
define('RUTA_CSS', SERVIDOR . 'css/');
define('RUTA_JS', SERVIDOR . 'js/');
define('RUTA_IMG', SERVIDOR . 'img/');
define('PLUGINS', SERVIDOR . 'plugins/');
define('DIST', SERVIDOR . 'dist/');
?>
