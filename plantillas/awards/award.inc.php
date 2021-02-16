<?php
switch ($cotizacion) {
  case 'gsa_buy_award':
    $canal = 'GSA-Buy';
    include_once 'plantillas/awards/gsa_buy_award.inc.php';
    break;
  case 'fedbid_award':
    $canal = 'FedBid';
    include_once 'plantillas/awards/fedbid_award.inc.php';
    break;
  case 'emails_award':
    $canal = 'E-mails';
    include_once 'plantillas/awards/emails_award.inc.php';
    break;
  case 'mailbox_award':
    $canal = 'Mailbox';
    include_once 'plantillas/awards/mailbox_award.inc.php';
    break;
  case 'findfrp_award':
    $canal = 'FindFRP';
    include_once 'plantillas/awards/findfrp_award.inc.php';
    break;
  case 'embassies_award':
    $canal = 'Embassies';
    include_once 'plantillas/awards/embassies_award.inc.php';
    break;
  case 'fbo_award':
    $canal = 'FBO';
    include_once 'plantillas/awards/fbo_award.inc.php';
    break;
  case 'chemonics_award':
    $canal = 'Chemonics';
    include_once 'plantillas/awards/chemonics_award.inc.php';
    break;
  case 'ebay_amazon_award':
    $canal = 'Ebay & Amazon';
    include_once 'plantillas/awards/ebay_amazon_award.inc.php';
    break;
}
?>
