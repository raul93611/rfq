<?php
switch ($cotizacion) {
  case 'gsa_buy':
    $canal = 'GSA-Buy';
    include_once 'plantillas/created/gsa_buy.inc.php';
    break;
  case 'fedbid':
    $canal = 'FedBid';
    include_once 'plantillas/created/fedbid.inc.php';
    break;
  case 'emails':
    $canal = 'E-mails';
    include_once 'plantillas/created/emails.inc.php';
    break;
  case 'mailbox':
    $canal = 'Mailbox';
    include_once 'plantillas/created/mailbox.inc.php';
    break;
  case 'findfrp':
    $canal = 'FindFRP';
    include_once 'plantillas/created/findfrp.inc.php';
    break;
  case 'embassies':
    $canal = 'Embassies';
    include_once 'plantillas/created/embassies.inc.php';
    break;
  case 'fbo':
    $canal = 'FBO';
    include_once 'plantillas/created/fbo.inc.php';
    break;
  case 'chemonics':
    $canal = 'Chemonics';
    include_once 'plantillas/created/chemonics.inc.php';
    break;
  case 'ebay_amazon':
    $canal = 'Ebay & Amazon';
    include_once 'plantillas/created/ebay_amazon.inc.php';
    break;
}
?>
