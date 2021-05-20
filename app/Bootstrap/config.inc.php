<?php
define('DATABASE_SERVER', 'localhost');
define('DATABASE_USER', 'root');
define('DATABASE_PASSWORD', '');
define('DATABASE_NAME', 'elogic');

define('SERVER', 'http://localhost/rfq/');
define('ERROR', SERVER . 'error');
/*****************************USER OPTIONS************************************/
define('PROFILE', SERVER . 'profile/');
define('LOGOUT', SERVER . 'logout');
define('REGISTER_USER', PROFILE . 'register_user');
define('EDIT_USER', PROFILE . 'edit_user/');
define('DISABLE_USER', SERVER . 'disable_user/');
define('ENABLE_USER', SERVER . 'enable_user/');
/********************************QUOTES*****************************************/
define('SEARCH_QUOTES', PROFILE . 'search_quotes');
define('QUOTES', PROFILE . 'quotes/');
define('GSA_BUY', QUOTES . 'gsa_buy');
define('FEDBID', QUOTES . 'fedbid');
define('EMAILS', QUOTES . 'emails');
define('MAILBOX', QUOTES . 'mailbox');
define('FINDRFP', QUOTES . 'findrfp');
define('EMBASSIES', QUOTES . 'embassies');
define('FBO', QUOTES . 'fbo');
define('CHEMONICS', QUOTES . 'chemonics');
define('EBAY_AMAZON', QUOTES . 'ebay_amazon');
/*********************************COMPLETED*************************************/
define('COMPLETE', PROFILE . 'complete/');
define('GSA_BUY_COMPLETE', COMPLETE . 'gsa_buy');
define('FEDBID_COMPLETE', COMPLETE . 'fedbid');
define('EMAILS_COMPLETE', COMPLETE . 'emails');
define('MAILBOX_COMPLETE', COMPLETE . 'mailbox');
define('FINDRFP_COMPLETE', COMPLETE . 'findrfp');
define('EMBASSIES_COMPLETE', COMPLETE . 'embassies');
define('FBO_COMPLETE', COMPLETE . 'fbo');
/*******************************SUBMITTED***************************************/
define('SUBMITTED', PROFILE . 'submitted/');
define('GSA_BUY_SUBMITTED', SUBMITTED . 'gsa_buy');
define('FEDBID_SUBMITTED', SUBMITTED . 'fedbid');
define('EMAILS_SUBMITTED', SUBMITTED . 'emails');
define('MAILBOX_SUBMITTED', SUBMITTED . 'mailbox');
define('FINDFRP_SUBMITTED', SUBMITTED . 'findrfp');
define('EMBASSIES_SUBMITTED', SUBMITTED . 'embassies');
define('FBO_SUBMITTED', SUBMITTED . 'fbo');
/****************************AWARD**********************************************/
define('AWARD', PROFILE . 'award/');
define('GSA_BUY_AWARD', AWARD . 'gsa_buy');
define('FEDBID_AWARD', AWARD . 'fedbid');
define('EMAILS_AWARD', AWARD . 'emails');
define('MAILBOX_AWARD', AWARD . 'mailbox');
define('FINDFRP_AWARD', AWARD . 'findrfp');
define('EMBASSIES_AWARD', AWARD . 'embassies');
define('FBO_AWARD', AWARD . 'fbo');
define('CHEMONICS_AWARD', AWARD . 'chemonics');
define('EBAY_AMAZON_AWARD', AWARD . 'ebay_amazon');
/***************************QUOTES OPTIONS**************************************/
define('COPY_QUOTE', SERVER . 'copy_quote/');
define('NEW_QUOTE', QUOTES . 'new');
define('EDIT_QUOTE', QUOTES . 'edit_quote');
define('DELETE_QUOTE', SERVER . 'delete_quote');
define('SAVE_EDIT_QUOTE', SERVER . 'save_edit_quote/');
define('SAVE_QUOTE_INFO', SERVER . 'save_quote_info');
/***************************OTHER SECTIONS**************************************/
define('NO_BID', PROFILE . 'no_bid');
define('NO_SUBMITTED', PROFILE . 'no_submitted');
define('CANCELLED', PROFILE . 'cancelled');
/***************************PROPOSAL********************************************/
define('PROPOSAL', SERVER . 'proposal');
define('PROPOSAL_GSA', SERVER . 'proposal_gsa');
/*************************ITEMS OPTIONS*****************************************/
define('ADD_ITEM', QUOTES . 'add_item');
define('DELETE_ITEM', SERVER . 'delete_item');
define('EDIT_ITEM', QUOTES . 'edit_item');
define('SAVE_ADD_ITEM', SERVER . 'save_add_item/');
define('SAVE_EDIT_ITEM', SERVER . 'save_edit_item/');
/*************************SUBITEMS OPTIONS***************************************/
define('DELETE_SUBITEM', SERVER . 'delete_subitem');
define('ADD_SUBITEM', QUOTES . 'add_subitem');
define('EDIT_SUBITEM', QUOTES . 'edit_subitem');
define('SAVE_ADD_SUBITEM', SERVER . 'save_add_subitem/');
define('SAVE_EDIT_SUBITEM', SERVER . 'save_edit_subitem/');
/**************************PROVIDER OPTIONS*************************************/
define('ADD_PROVIDER', QUOTES . 'add_provider');
define('EDIT_PROVIDER', QUOTES . 'edit_provider');
define('DELETE_PROVIDER', SERVER . 'delete_provider');
define('SAVE_ADD_PROVIDER', SERVER . 'save_add_provider/');
define('SAVE_EDIT_PROVIDER', SERVER . 'save_edit_provider/');
/***************************PROVIDER SUBITEMS OPTIONS**************************/
define('ADD_PROVIDER_SUBITEM', QUOTES . 'add_provider_subitem');
define('EDIT_PROVIDER_SUBITEM', QUOTES . 'edit_provider_subitem');
define('DELETE_PROVIDER_SUBITEM', SERVER . 'delete_provider_subitem');
define('SAVE_ADD_PROVIDER_SUBITEM', SERVER . 'save_add_provider_subitem/');
define('SAVE_EDIT_PROVIDER_SUBITEM', SERVER . 'save_edit_provider_subitem/');
/**************************************************************************************/
define('PDF_REPORT', SERVER . 'pdf_report');
/************************************************************************************/
define('SAVE_COMMENT', SERVER . 'save_comment/');
/**********************************************************************************/
define('PDF_TABLA_ITEMS', SERVER . 'pdf_tabla_items/');
define('PDF_RE_QUOTE', SERVER . 'pdf_re_quote/');
define('EXCEL_ITEMS_TABLE', SERVER . 'excel_items_table/');
/**********************************************************************************/
define('EXCEL_REPORTS', PROFILE . 'excel_reports');
define('GENERATE_EXCEL_REPORT', SERVER . 'generate_excel_report');
/*************************************************************************************/
define('EMPLOYEE_DOCS_PAGE', PROFILE . 'employee_docs_page/');
/********************************************************************************/
define('DELETE_DOCUMENT', SERVER . 'delete_document');
/********************************************************************************/
define('RECOVER_PASSWORD_FORM', SERVER . 'recover_password_form');
define('RESTART_PASSWORD', SERVER . 'restart_password/');
/***************************************************************************************/
define('RE_QUOTE', PROFILE . 're_quote/');
define('RELOAD_REQUOTE', SERVER . 'reload_requote/');
define('SAVE_RE_QUOTE', SERVER . 'save_re_quote');
define('ADD_RE_QUOTE_ITEM', PROFILE . 'add_re_quote_item/');
define('SAVE_RE_QUOTE_ITEM', SERVER . 'save_re_quote_item');
define('EDIT_RE_QUOTE_ITEM', PROFILE . 'edit_re_quote_item/');
define('SAVE_EDIT_RE_QUOTE_ITEM', SERVER . 'save_edit_re_quote_item');
define('DELETE_RE_QUOTE_ITEM', SERVER . 'delete_re_quote_item/');
define('ADD_RE_QUOTE_PROVIDER', PROFILE . 'add_re_quote_provider/');
define('SAVE_RE_QUOTE_PROVIDER', SERVER . 'save_re_quote_provider');
define('EDIT_RE_QUOTE_PROVIDER', PROFILE . 'edit_re_quote_provider/');
define('SAVE_EDIT_RE_QUOTE_PROVIDER', SERVER . 'save_edit_re_quote_provider');
define('DELETE_RE_QUOTE_PROVIDER', SERVER . 'delete_re_quote_provider/');
define('ADD_RE_QUOTE_SUBITEM', PROFILE . 'add_re_quote_subitem/');
define('SAVE_RE_QUOTE_SUBITEM', SERVER . 'save_re_quote_subitem/');
define('EDIT_RE_QUOTE_SUBITEM', PROFILE . 'edit_re_quote_subitem/');
define('SAVE_EDIT_RE_QUOTE_SUBITEM', SERVER . 'save_edit_re_quote_subitem');
define('DELETE_RE_QUOTE_SUBITEM', SERVER . 'delete_re_quote_subitem/');
define('ADD_RE_QUOTE_SUBITEM_PROVIDER', PROFILE . 'add_re_quote_subitem_provider/');
define('SAVE_RE_QUOTE_SUBITEM_PROVIDER', SERVER . 'save_re_quote_subitem_provider');
define('EDIT_RE_QUOTE_SUBITEM_PROVIDER', PROFILE . 'edit_re_quote_subitem_provider/');
define('SAVE_EDIT_RE_QUOTE_SUBITEM_PROVIDER', SERVER . 'save_edit_re_quote_subitem_provider');
define('DELETE_RE_QUOTE_SUBITEM_PROVIDER', SERVER . 'delete_re_quote_subitem_provider/');
/***********************************************************************************/
define('FULFILLMENT_QUOTES', PROFILE . 'fulfillment_quotes');
/******************************************************************************/
define('REMOVE_AWARD', SERVER . 'remove_award/');
define('REMOVE_FULFILLMENT', SERVER . 'remove_fulfillment/');
/******************************************************************************/
define('ADD_SERVICE', SERVER . 'add_service');
define('LOAD_SERVICE', SERVER . 'load_service/');
define('EDIT_SERVICE', SERVER . 'edit_service');
define('DELETE_SERVICE', SERVER . 'delete_service/');
/******************************************************************************/
define('TRACKING', PROFILE . 'tracking/');
define('SAVE_TRACKING', SERVER . 'save_tracking');
define('SAVE_TRACKING_SUBITEM', SERVER . 'save_tracking_subitem');
define('DELETE_TRACKING', SERVER . 'delete_tracking/');
define('DELETE_TRACKING_SUBITEM', SERVER . 'delete_tracking_subitem/');
define('TRACKING_PDF', SERVER . 'tracking_pdf/');
/******************************************************************************/
define('FULFILLMENT', PROFILE . 'fulfillment/');
define('SAVE_FULFILLMENT_ITEM', SERVER . 'save_fulfillment_item');
define('SAVE_FULFILLMENT_SUBITEM', SERVER . 'save_fulfillment_subitem');
define('DELETE_FULFILLMENT_ITEM', SERVER . 'delete_fulfillment_item/');
define('DELETE_FULFILLMENT_SUBITEM', SERVER . 'delete_fulfillment_subitem/');
/******************************************************************************/
define('SAVE_FULFILLMENT_SERVICE', SERVER . 'save_fulfillment_service');
/******************************************************************************/
define('EMPLOYEE_DOCS', SERVER . 'employee_docs/');
define('REPORTS_DOCS', SERVER . 'reports/');
define('DOCS', SERVER . 'documents/');
define('CSS_PATH', SERVER . 'css/');
define('JS_PATH', SERVER . 'js/');
define('IMG_PATH', SERVER . 'img/');
define('PLUGINS', SERVER . 'plugins/');
define('DIST', SERVER . 'dist/');
?>
