<?php
define('NOMBRE_SERVIDOR', 'localhost');
define('NOMBRE_USUARIO', 'root');
define('PASSWORD', 'raul93611');
define('NOMBRE_BD', 'elogic');

define('SERVER_NAME', 'localhost');
define('USER_NAME', 'root');
define('BD_NAME', 'rfp');

define('SERVERNAME_FULLFILLMENT', 'localhost');
define('BD_NAME_FULLFILLMENT', 'fullfillment');
define('USERNAME_FULLFILLMENT', 'root');
define('PASSWORD_FULLFILLMENT', 'raul93611');

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
/***************************QUOTES OPTIONS**************************************/
define('COPY_QUOTE', SERVIDOR . 'copy_quote/');
define('NUEVA_COTIZACION', COTIZACIONES . 'nuevo');
define('EDITAR_COTIZACION', COTIZACIONES . 'editar_cotizacion');
define('DELETE_QUOTE', SERVIDOR . 'delete_quote');
define('GUARDAR_EDITAR_COTIZACION', SERVIDOR . 'guardar_editar_cotizacion/');
/***************************OTHER SECTIONS**************************************/
define('NO_BID', COTIZACIONES . 'no_bid');
define('NO_SUBMITTED', COTIZACIONES . 'no_submitted');
define('RFP_QUOTES', COTIZACIONES . 'rfp_quotes');

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
/******************************CUESTIONARIO**************************************/
define('CUESTIONARIO', COTIZACIONES . 'cuestionario');
define('GUARDAR_CUESTIONARIO', SERVIDOR . 'guardar_cuestionario/');
/**********************************************************************************/
define('ADD_HIGH_LEVEL_REQUIREMENT', COTIZACIONES . 'add_high_level_requirement');
define('GUARDAR_ADD_HIGH_LEVEL_REQUIREMENT', SERVIDOR . 'guardar_add_high_level_requirement/');
define('EDIT_HIGH_LEVEL_REQUIREMENT', COTIZACIONES . 'edit_high_level_requirement');
define('GUARDAR_EDIT_HIGH_LEVEL_REQUIREMENT', SERVIDOR . 'guardar_edit_high_level_requirement/');
/*********************************************************************************/
define('ADD_OUT_OF_SCOPE', COTIZACIONES . 'add_out_of_scope');
define('GUARDAR_ADD_OUT_OF_SCOPE', SERVIDOR . 'guardar_add_out_of_scope/');
define('EDIT_OUT_OF_SCOPE', COTIZACIONES . 'edit_out_of_scope');
define('GUARDAR_EDIT_OUT_OF_SCOPE', SERVIDOR . 'guardar_edit_out_of_scope/');
/**********************************************************************************/
define('ADD_PROJECT_RISK', COTIZACIONES . 'add_project_risk');
define('GUARDAR_ADD_PROJECT_RISK', SERVIDOR . 'guardar_add_project_risk/');
define('EDIT_PROJECT_RISK', COTIZACIONES . 'edit_project_risk');
define('GUARDAR_EDIT_PROJECT_RISK', SERVIDOR . 'guardar_edit_project_risk/');
/***********************************************************************************/
define('ADD_PROJECT_MILESTONE', COTIZACIONES . 'add_project_milestone');
define('GUARDAR_ADD_PROJECT_MILESTONE', SERVIDOR . 'guardar_add_project_milestone/');
define('EDIT_PROJECT_MILESTONE', COTIZACIONES . 'edit_project_milestone');
define('GUARDAR_EDIT_PROJECT_MILESTONE', SERVIDOR . 'guardar_edit_project_milestone/');
/**************************************************************************************/
define('PDF_REPORT', SERVIDOR . 'pdf_report');
define('PDF_PROJECT_CHARTER', SERVIDOR . 'pdf_project_charter/');
/************************************************************************************/
define('GUARDAR_COMMENT', SERVIDOR . 'guardar_comment/');
define('HISTORIAL_COMMENTS', PERFIL . 'historial_comments/');
/*************************************************************************************/
define('CREATE_PROJECT', SERVIDOR . 'create_project/');
/***********************************************************************************/
define('GUARDAR_FULLFILLMENT_FORM', SERVIDOR . 'guardar_fullfillment_form');
/**********************************************************************************/
define('PDF_TABLA_ITEMS', SERVIDOR . 'pdf_tabla_items/');
/**********************************************************************************/
define('EXCEL_REPORT', SERVIDOR . 'excel_report');
/*************************************************************************************/
define('EMPLOYEE_DOCS_PAGE', PERFIL . 'employee_docs_page/');
/********************************************************************************/
define('DELETE_DOCUMENT', SERVIDOR . 'delete_document');

define('EMPLOYEE_DOCS', SERVIDOR . 'employee_docs/');
define('REPORTS_DOCS', SERVIDOR . 'reports/');
define('DOCS', SERVIDOR . 'documentos/');
define('RUTA_CSS', SERVIDOR . 'css/');
define('RUTA_JS', SERVIDOR . 'js/');
define('RUTA_IMG', SERVIDOR . 'img/');
define('PLUGINS', SERVIDOR . 'plugins/');
define('DIST', SERVIDOR . 'dist/');
?>
