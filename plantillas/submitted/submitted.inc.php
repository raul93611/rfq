<?php
switch ($cotizacion) {
  case 'gsa_buy_submitted':
    $canal = 'GSA-Buy';
    include_once 'plantillas/submitted/gsa_buy_submitted.inc.php';
    break;
  case 'fedbid_submitted':
    $canal = 'FedBid';
    include_once 'plantillas/submitted/fedbid_submitted.inc.php';
    break;
  case 'emails_submitted':
    $canal = 'E-mails';
    include_once 'plantillas/submitted/emails_submitted.inc.php';
    break;
  case 'mailbox_submitted':
    $canal = 'Mailbox';
    include_once 'plantillas/submitted/mailbox_submitted.inc.php';
    break;
  case 'findfrp_submitted':
    $canal = 'FindFRP';
    include_once 'plantillas/submitted/findfrp_submitted.inc.php';
    break;
  case 'embassies_submitted':
    $canal = 'Embassies';
    include_once 'plantillas/submitted/embassies_submitted.inc.php';
    break;
  case 'fbo_submitted':
    $canal = 'FBO';
    include_once 'plantillas/submitted/fbo_submitted.inc.php';
    break;
}
?>
