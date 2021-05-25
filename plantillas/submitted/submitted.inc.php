<?php
switch ($quote) {
  case 'gsa_buy_submitted':
    $channel = 'GSA-Buy';
    include_once 'plantillas/submitted/gsa_buy_submitted.inc.php';
    break;
  case 'fedbid_submitted':
    $channel = 'FedBid';
    include_once 'plantillas/submitted/fedbid_submitted.inc.php';
    break;
  case 'emails_submitted':
    $channel = 'E-mails';
    include_once 'plantillas/submitted/emails_submitted.inc.php';
    break;
  case 'mailbox_submitted':
    $channel = 'Mailbox';
    include_once 'plantillas/submitted/mailbox_submitted.inc.php';
    break;
  case 'findfrp_submitted':
    $channel = 'FindFRP';
    include_once 'plantillas/submitted/findfrp_submitted.inc.php';
    break;
  case 'embassies_submitted':
    $channel = 'Embassies';
    include_once 'plantillas/submitted/embassies_submitted.inc.php';
    break;
  case 'fbo_submitted':
    $channel = 'FBO';
    include_once 'plantillas/submitted/fbo_submitted.inc.php';
    break;
}
?>
