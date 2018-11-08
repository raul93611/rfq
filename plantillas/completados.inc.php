<?php
switch ($cotizacion) {
  case 'gsa_buy_completados':
    $canal = 'GSA-Buy';
    include_once 'plantillas/gsa_buy_completados.inc.php';
    break;
  case 'fedbid_completados':
    $canal = 'FedBid';
    include_once 'plantillas/fedbid_completados.inc.php';
    break;
  case 'emails_completados':
    $canal = 'E-mails';
    include_once 'plantillas/emails_completados.inc.php';
    break;
  case 'mailbox_completados':
    $canal = 'Mailbox';
    include_once 'plantillas/mailbox_completados.inc.php';
    break;
  case 'findfrp_completados':
    $canal = 'FindFRP';
    include_once 'plantillas/findfrp_completados.inc.php';
    break;
  case 'embassies_completados':
    $canal = 'Embassies';
    include_once 'plantillas/embassies_completados.inc.php';
    break;
  case 'fbo_completados':
    $canal = 'FBO';
    include_once 'plantillas/fbo_completados.inc.php';
    break;
}
?>
