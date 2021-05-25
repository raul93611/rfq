<?php
switch ($quote) {
  case 'gsa_buy_completados':
    $channel = 'GSA-Buy';
    include_once 'plantillas/completed/gsa_buy_completados.inc.php';
    break;
  case 'fedbid_completados':
    $channel = 'FedBid';
    include_once 'plantillas/completed/fedbid_completados.inc.php';
    break;
  case 'emails_completados':
    $channel = 'E-mails';
    include_once 'plantillas/completed/emails_completados.inc.php';
    break;
  case 'mailbox_completados':
    $channel = 'Mailbox';
    include_once 'plantillas/completed/mailbox_completados.inc.php';
    break;
  case 'findfrp_completados':
    $channel = 'FindFRP';
    include_once 'plantillas/completed/findfrp_completados.inc.php';
    break;
  case 'embassies_completados':
    $channel = 'Embassies';
    include_once 'plantillas/completed/embassies_completados.inc.php';
    break;
  case 'fbo_completados':
    $channel = 'FBO';
    include_once 'plantillas/completed/fbo_completados.inc.php';
    break;
}
?>
