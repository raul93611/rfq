<?php
switch ($cotizacion) {
  case 'gsa_buy_award':
    $canal = 'GSA-Buy';
    include_once 'plantillas/gsa_buy_award.inc.php';
    break;
  case 'fedbid_award':
    $canal = 'FedBid';
    include_once 'plantillas/fedbid_award.inc.php';
    break;
  case 'emails_award':
    $canal = 'E-mails';
    include_once 'plantillas/emails_award.inc.php';
    break;
  case 'mailbox_award':
    $canal = 'Mailbox';
    include_once 'plantillas/mailbox_award.inc.php';
    break;
  case 'findfrp_award':
    $canal = 'FindFRP';
    include_once 'plantillas/findfrp_award.inc.php';
    break;
  case 'embassies_award':
    $canal = 'Embassies';
    include_once 'plantillas/embassies_award.inc.php';
    break;
  case 'fbo_award':
    $canal = 'FBO';
    include_once 'plantillas/fbo_award.inc.php';
    break;
}
?>
