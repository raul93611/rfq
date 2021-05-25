<?php
switch ($quote) {
  case 'gsa_buy':
    $channel = 'GSA-Buy';
    include_once 'plantillas/created/gsa_buy.inc.php';
    break;
  case 'fedbid':
    $channel = 'FedBid';
    include_once 'plantillas/created/fedbid.inc.php';
    break;
  case 'emails':
    $channel = 'E-mails';
    include_once 'plantillas/created/emails.inc.php';
    break;
  case 'mailbox':
    $channel = 'Mailbox';
    include_once 'plantillas/created/mailbox.inc.php';
    break;
  case 'findrfp':
    $channel = 'FindFRP';
    include_once 'plantillas/created/findrfp.inc.php';
    break;
  case 'embassies':
    $channel = 'Embassies';
    include_once 'plantillas/created/embassies.inc.php';
    break;
  case 'fbo':
    $channel = 'FBO';
    include_once 'plantillas/created/fbo.inc.php';
    break;
  case 'chemonics':
    $channel = 'Chemonics';
    include_once 'plantillas/created/chemonics.inc.php';
    break;
  case 'ebay_amazon':
    $channel = 'Ebay & Amazon';
    include_once 'plantillas/created/ebay_amazon.inc.php';
    break;
}
?>
