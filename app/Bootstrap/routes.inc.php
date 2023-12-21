<?php
define('ERROR', SERVIDOR . 'error');
/*****************************USER OPTIONS************************************/
define('PERFIL', SERVIDOR . 'perfil/');
define('LOGOUT', SERVIDOR . 'logout');
/********************************QUOTE SCRIPTS*****************************************/
define('QUOTE_SC', SERVIDOR . 'quote/');
define('REMOVE_SLAVE', QUOTE_SC . 'remove_slave/');
define('REMOVE_MASTER', QUOTE_SC . 'remove_master/');
define('GUARDAR_COMMENT', QUOTE_SC . 'guardar_comment/');
define('SAVE_QUOTE_INFO', QUOTE_SC . 'save_quote_info');
define('PROPOSAL', QUOTE_SC . 'proposal');
define('PROPOSAL_GSA', QUOTE_SC . 'proposal_gsa');
define('COPY_QUOTE', QUOTE_SC . 'copy_quote/');
define('DELETE_QUOTE', QUOTE_SC . 'delete_quote');
define('GUARDAR_EDITAR_COTIZACION', QUOTE_SC . 'guardar_editar_cotizacion/');
define('PDF_TABLA_ITEMS', QUOTE_SC . 'pdf_tabla_items/');
define('EXCEL_ITEMS_TABLE', QUOTE_SC . 'excel_items_table/');
define('REMOVE_AWARD', QUOTE_SC . 'remove_award/');
define('REMOVE_FULFILLMENT', QUOTE_SC . 'remove_fulfillment/');
define('REMOVE_INVOICE', QUOTE_SC . 'remove_invoice/');
define('REMOVE_SUBMITTED_INVOICE', QUOTE_SC . 'remove_submitted_invoice/');
define('SAVE_CHECKLIST', QUOTE_SC . 'save_checklist/');
define('SAVE_INFORMATION', QUOTE_SC . 'save_information/');
define('GENERATE_CHECKLIST_PDF', QUOTE_SC . 'generate_checklist_pdf/');
define('LINK_QUOTE', QUOTE_SC . 'link_quote/');
/********************************SERVICE SCRIPTS*****************************************/
define('SERVICE_SC', QUOTE_SC . 'service/');
define('ADD_SERVICE', SERVICE_SC . 'add_service');
define('EDIT_SERVICE', SERVICE_SC . 'edit_service');
define('DELETE_SERVICE', SERVICE_SC . 'delete_service/');
/********************************EQUIPMENT SCRIPTS*****************************************/
define('EQUIPMENT_SC', QUOTE_SC . 'equipment/');
define('GUARDAR_ADD_ITEM', EQUIPMENT_SC . 'guardar_add_item/');
define('GUARDAR_ADD_PROVIDER', EQUIPMENT_SC . 'guardar_add_provider/');
define('GUARDAR_EDIT_ITEM', EQUIPMENT_SC . 'guardar_edit_item/');
define('DELETE_ITEM', EQUIPMENT_SC . 'delete_item');
define('GUARDAR_EDIT_PROVIDER', EQUIPMENT_SC . 'guardar_edit_provider/');
define('GUARDAR_ADD_SUBITEM', EQUIPMENT_SC . 'guardar_add_subitem/');
define('GUARDAR_ADD_PROVIDER_SUBITEM', EQUIPMENT_SC . 'guardar_add_provider_subitem/');
define('GUARDAR_EDIT_SUBITEM', EQUIPMENT_SC . 'guardar_edit_subitem/');
define('GUARDAR_EDIT_PROVIDER_SUBITEM', EQUIPMENT_SC . 'guardar_edit_provider_subitem/');
define('DELETE_PROVIDER', EQUIPMENT_SC . 'delete_provider');
define('DELETE_PROVIDER_SUBITEM', EQUIPMENT_SC . 'delete_provider_subitem');
define('DELETE_SUBITEM', EQUIPMENT_SC . 'delete_subitem');
/********************************TRACKING SCRIPTS*****************************************/
define('TRACKING_SC', SERVIDOR . 'tracking/');
define('SAVE_TRACKING', TRACKING_SC . 'save_tracking');
define('SAVE_TRACKING_SUBITEM', TRACKING_SC . 'save_tracking_subitem');
define('DELETE_TRACKING', TRACKING_SC . 'delete_tracking/');
define('DELETE_TRACKING_SUBITEM', TRACKING_SC . 'delete_tracking_subitem/');
define('TRACKING_PDF', TRACKING_SC . 'tracking_pdf/');
define('TRACKING_EXCEL', TRACKING_SC . 'tracking_excel/');
/********************************USER SCRIPTS*****************************************/
define('USER_SC', SERVIDOR . 'user/');
define('RECOVER_PASSWORD_FORM', USER_SC . 'recover_password_form');
define('ENABLE_USER', USER_SC . 'enable_user/');
define('DISABLE_USER', USER_SC . 'disable_user/');
define('RESTART_PASSWORD', USER_SC . 'restart_password/');
/********************************RE QUOTE SCRIPTS*****************************************/
define('RE_QUOTE_SC', SERVIDOR . 're_quote_sc/');
define('SAVE_RE_QUOTE_ITEM', RE_QUOTE_SC . 'save_re_quote_item');
define('SAVE_EDIT_RE_QUOTE_ITEM', RE_QUOTE_SC . 'save_edit_re_quote_item');
define('SAVE_RE_QUOTE_PROVIDER', RE_QUOTE_SC . 'save_re_quote_provider');
define('SAVE_EDIT_RE_QUOTE_PROVIDER', RE_QUOTE_SC . 'save_edit_re_quote_provider');
define('SAVE_RE_QUOTE_SUBITEM', RE_QUOTE_SC . 'save_re_quote_subitem/');
define('SAVE_EDIT_RE_QUOTE_SUBITEM', RE_QUOTE_SC . 'save_edit_re_quote_subitem');
define('SAVE_RE_QUOTE_SUBITEM_PROVIDER', RE_QUOTE_SC . 'save_re_quote_subitem_provider');
define('SAVE_EDIT_RE_QUOTE_SUBITEM_PROVIDER', RE_QUOTE_SC . 'save_edit_re_quote_subitem_provider');
define('SAVE_RE_QUOTE', RE_QUOTE_SC . 'save_re_quote');
define('PDF_RE_QUOTE', RE_QUOTE_SC . 'pdf_re_quote/');
define('DELETE_RE_QUOTE_PROVIDER', RE_QUOTE_SC . 'delete_re_quote_provider/');
define('DELETE_RE_QUOTE_SUBITEM_PROVIDER', RE_QUOTE_SC . 'delete_re_quote_subitem_provider/');
define('DELETE_RE_QUOTE_SUBITEM', RE_QUOTE_SC . 'delete_re_quote_subitem/');
define('DELETE_RE_QUOTE_ITEM', RE_QUOTE_SC . 'delete_re_quote_item/');
define('RELOAD_REQUOTE', RE_QUOTE_SC . 'reload_requote/');
define('UPDATE_SERVICE_RE_QUOTE', RE_QUOTE_SC . 'update_service');
/********************************FULFILLMENT SCRIPTS*****************************************/
define('FULFILLMENT_SC', SERVIDOR . 'fulfillment/');
define('MARK_AS_PENDING', FULFILLMENT_SC . 'mark_as_pending/');
define('UNMARK_AS_PENDING', FULFILLMENT_SC . 'unmark_as_pending/');
define('DELETE_INVOICE', FULFILLMENT_SC . 'delete_invoice/');
define('UPDATE_INVOICE', FULFILLMENT_SC . 'update_invoice/');
/********************************USER VIEWS*****************************************/
define('USER', PERFIL . 'user/');
define('REGISTRO', USER . 'registro');
define('USERS', USER . 'users');
define('EDIT_USER', USER . 'edit_user/');
/********************************QUOTE VIEWS*****************************************/
define('QUOTE', PERFIL . 'quote/');
define('NO_BID', QUOTE . 'no_bid');
define('NO_SUBMITTED', QUOTE . 'no_submitted');
define('CANCELLED', QUOTE . 'cancelled');
define('DELETED', QUOTE . 'deleted');
define('NUEVA_COTIZACION', QUOTE . 'nuevo');
define('EDITAR_COTIZACION', QUOTE . 'editar_cotizacion');
define('CHECKLIST', QUOTE . 'checklist/');
define('INFORMATION', QUOTE . 'information/');
/********************************CHANNELS VIEWS*****************************************/
define('CHANNEL', QUOTE . 'channel/');
define('GSA_BUY', CHANNEL . 'GSA-Buy');
define('FEDBID', CHANNEL . 'FedBid');
define('EMAILS', CHANNEL . 'E-mails');
define('MAILBOX', CHANNEL . 'Mailbox');
define('FINDFRP', CHANNEL . 'FindFRP');
define('EMBASSIES', CHANNEL . 'Embassies');
define('FBO', CHANNEL . 'FBO');
define('SEAPORT', CHANNEL . 'Seaport');
define('CHEMONICS', CHANNEL . 'Chemonics');
define('EBAY_AMAZON', CHANNEL . 'Ebay & Amazon');
define('STARSIII', CHANNEL . 'Stars III');
/*********************************COMPLETED*************************************/
define('COMPLETED', QUOTE . 'completed/');
define('GSA_BUY_COMPLETADOS', COMPLETED . 'GSA-Buy');
define('FEDBID_COMPLETADOS', COMPLETED . 'FedBid');
define('EMAILS_COMPLETADOS', COMPLETED . 'E-mails');
define('MAILBOX_COMPLETADOS', COMPLETED . 'Mailbox');
define('FINDFRP_COMPLETADOS', COMPLETED . 'FindFRP');
define('EMBASSIES_COMPLETADOS', COMPLETED . 'Embassies');
define('FBO_COMPLETADOS', COMPLETED . 'FBO');
define('SEAPORT_COMPLETADOS', COMPLETED . 'Seaport');
define('EBAY_AMAZON_COMPLETADOS', COMPLETED . 'Ebay & Amazon');
define('STARSIII_COMPLETADOS', COMPLETED . 'Stars III');
/*******************************SUBMITTED***************************************/
define('SUBMITTED', QUOTE . 'submitted/');
define('GSA_BUY_SUBMITTED', SUBMITTED . 'GSA-Buy');
define('FEDBID_SUBMITTED', SUBMITTED . 'FedBid');
define('EMAILS_SUBMITTED', SUBMITTED . 'E-mails');
define('MAILBOX_SUBMITTED', SUBMITTED . 'Mailbox');
define('FINDFRP_SUBMITTED', SUBMITTED . 'FindFRP');
define('EMBASSIES_SUBMITTED', SUBMITTED . 'Embassies');
define('FBO_SUBMITTED', SUBMITTED . 'FBO');
define('SEAPORT_SUBMITTED', SUBMITTED . 'Seaport');
define('EBAY_AMAZON_SUBMITTED', SUBMITTED . 'Ebay & Amazon');
define('STARSIII_SUBMITTED', SUBMITTED . 'Stars III');
/****************************AWARD**********************************************/
define('AWARD', QUOTE . 'award/');
define('GSA_BUY_AWARD', AWARD . 'GSA-Buy');
define('FEDBID_AWARD', AWARD . 'FedBid');
define('EMAILS_AWARD', AWARD . 'E-mails');
define('MAILBOX_AWARD', AWARD . 'Mailbox');
define('FINDFRP_AWARD', AWARD . 'FindFRP');
define('EMBASSIES_AWARD', AWARD . 'Embassies');
define('FBO_AWARD', AWARD . 'FBO');
define('SEAPORT_AWARD', AWARD . 'Seaport');
define('CHEMONICS_AWARD', AWARD . 'Chemonics');
define('EBAY_AMAZON_AWARD', AWARD . 'Ebay & Amazon');
define('STARSIII_AWARD', AWARD . 'Stars III');
/********************************EQUIPMENT VIEWS*****************************************/
define('EQUIPMENT', QUOTE . 'equipment/');
define('ADD_ITEM', EQUIPMENT . 'add_item');
define('ADD_PROVIDER', EQUIPMENT . 'add_provider');
define('ADD_PROVIDER_SUBITEM', EQUIPMENT . 'add_provider_subitem');
define('ADD_SUBITEM', EQUIPMENT . 'add_subitem');
define('EDIT_ITEM', EQUIPMENT . 'edit_item');
define('EDIT_SUBITEM', EQUIPMENT . 'edit_subitem');
define('EDIT_PROVIDER', EQUIPMENT . 'edit_provider');
define('EDIT_PROVIDER_SUBITEM', EQUIPMENT . 'edit_provider_subitem');
/********************************RE QUOTE VIEWS*****************************************/
define('RE_QUOTE', PERFIL . 're_quote/');
define('ADD_RE_QUOTE_ITEM', RE_QUOTE . 'add_re_quote_item/');
define('EDIT_RE_QUOTE_ITEM', RE_QUOTE . 'edit_re_quote_item/');
define('ADD_RE_QUOTE_PROVIDER', RE_QUOTE . 'add_re_quote_provider/');
define('EDIT_RE_QUOTE_PROVIDER', RE_QUOTE . 'edit_re_quote_provider/');
define('ADD_RE_QUOTE_SUBITEM', RE_QUOTE . 'add_re_quote_subitem/');
define('EDIT_RE_QUOTE_SUBITEM', RE_QUOTE . 'edit_re_quote_subitem/');
define('ADD_RE_QUOTE_SUBITEM_PROVIDER', RE_QUOTE . 'add_re_quote_subitem_provider/');
define('EDIT_RE_QUOTE_SUBITEM_PROVIDER', RE_QUOTE . 'edit_re_quote_subitem_provider/');
/********************************FULFILLMENT VIEWS*****************************************/
define('FULFILLMENT', PERFIL . 'fulfillment/');
define('FULFILLMENT_QUOTES', FULFILLMENT . 'fulfillment_quotes');
define('PROVIDERS', FULFILLMENT . 'providers');
define('PAYMENT_TERMS', FULFILLMENT . 'payment_terms');
define('PERSONNEL_CALENDAR', FULFILLMENT . 'personnel_calendar');
define('PERSONNEL', FULFILLMENT . 'personnel');
define('TYPE_OF_PROJECT', FULFILLMENT . 'type_of_project');
define('INVOICE', FULFILLMENT . 'invoice/');
/********************************PROJECTIONS VIEWS*****************************************/
define('PROJECTION', PERFIL . 'projection/');
define('DAILY', PROJECTION . 'daily');
define('WEEKLY_PROJECTIONS_2023', PROJECTION . 'weekly_projections_2023');
define('WEEKLY_PROJECTIONS_2022', PROJECTION . 'weekly_projections_2022');
/********************************ACCOUNTING_CHECKBOX VIEWS*****************************************/
define('ACCOUNTING', PERFIL . 'accounting/');
define('INVOICE_QUOTES', ACCOUNTING . 'invoice_quotes');
define('SUBMITTED_INVOICE_QUOTES', ACCOUNTING . 'submitted_invoice_quotes');
/********************************TASKS VIEWS*****************************************/
define('TASKS', PERFIL . 'tasks/');
define('MY_TASKS', TASKS . 'my_tasks');
define('TASKS_DONE', TASKS . 'tasks_done');
define('ALL_TASKS', TASKS . 'all_tasks');
/********************************REPORTS VIEWS*****************************************/
define('REPORTS', PERFIL . 'reports/');
define('REPORTS_TABLES', REPORTS . 'reports_tables');
define('REPORTS_CHARTS', REPORTS . 'reports_charts');
/********************************OTHER VIEWS*****************************************/
define('CHARTS', PERFIL . 'charts');
define('SEARCH_QUOTES', PERFIL . 'search_quotes');
define('TRACKING', PERFIL . 'tracking/');
define('KPI', PERFIL . 'kpi/');
define('EMPLOYEE_DOCS_PAGE', PERFIL . 'employee_docs_page/');
/******************************************************************************/
define('EMPLOYEE_DOCS', SERVIDOR . 'employee_docs/');
define('DOCS', SERVIDOR . 'documentos/');
define('RUTA_CSS', SERVIDOR . 'css/');
define('RUTA_JS', SERVIDOR . 'js/');
define('RUTA_IMG', SERVIDOR . 'img/');
define('STATES', [
  'AL' => 'Alabama',
  'AK' => 'Alaska',
  'AZ' => 'Arizona',
  'AR' => 'Arkansas',
  'CA' => 'California',
  'CO' => 'Colorado',
  'CT' => 'Connecticut',
  'DE' => 'Delaware',
  'DC' => 'District of Columbia',
  'FL' => 'Florida',
  'GA' => 'Georgia',
  'HI' => 'Hawaii',
  'ID' => 'Idaho',
  'IL' => 'Illinois',
  'IN' => 'Indiana',
  'IA' => 'Iowa',
  'KS' => 'Kansas',
  'KY' => 'Kentucky',
  'LA' => 'Louisiana',
  'ME' => 'Maine',
  'MD' => 'Maryland',
  'MA' => 'Massachusetts',
  'MI' => 'Michigan',
  'MN' => 'Minnesota',
  'MS' => 'Mississippi',
  'MO' => 'Missouri',
  'MT' => 'Montana',
  'NE' => 'Nebraska',
  'NV' => 'Nevada',
  'NH' => 'New Hampshire',
  'NJ' => 'New Jersey',
  'NM' => 'New Mexico',
  'NY' => 'New York',
  'NC' => 'North Carolina',
  'ND' => 'North Dakota',
  'OH' => 'Ohio',
  'OK' => 'Oklahoma',
  'OR' => 'Oregon',
  'PA' => 'Pennsylvania',
  'RI' => 'Rhode Island',
  'SC' => 'South Carolina',
  'SD' => 'South Dakota',
  'TN' => 'Tennessee',
  'TX' => 'Texas',
  'UT' => 'Utah',
  'VT' => 'Vermont',
  'VA' => 'Virginia',
  'WA' => 'Washington',
  'WV' => 'West Virginia',
  'WI' => 'Wisconsin',
  'WY' => 'Wyoming',
  'OTHER' => 'Other',
]);
define('SET_SIDE', [
  'Full & Open',
  'SB',
  '8A',
  'HUBZONE',
]);
define('FILE_DOCUMENT', [
  'proposal' => 'Proposal',
  'contract_confirmation' => 'Contract/Confirmation',
  'original_request' => 'Original Request (RFQ/RFP)',
  'requote_original' => 'Re - Quote Original',
  'sow_expenses' => 'SOW Expenses',
  'draft_pmp' => 'Draft PMP',
  'cc_authorization_format' => 'CC Authorization Format',
  'email_confirmation' => 'Email Confirmation',
]);
define('ACCOUNTING_CHECKBOX', [
  'dos_payment' => 'DOS Payment',
  'wawf' => 'WAWF',
  'ipp' => 'IPP',
  'dc_portal' => 'DC Portal',
  'syncada' => 'Syncada',
  'email' => 'E-mail',
]);
define('SHIPPING_ADDRESS', [
  'apo' => 'APO',
  'pouch' => 'POUCH',
  'client_location' => 'CLIENT LOCATION',
  'f_forward' => 'F FORWARD',
]);
define('GSA', [
  'na' => 'N/A',
  'open_market' => 'Open Market',
  'gsa_price' => 'GSA Price'
]);
define('CLIENT_PAYMENT_TERMS', [
  'net_30' => 'Net 30',
  'cc' => 'CC'
]);
?>
