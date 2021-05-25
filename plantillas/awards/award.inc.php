<?php
switch ($quote) {
  case 'gsa_buy_award':
    $channel = 'GSA-Buy';
    include_once 'plantillas/awards/gsa_buy_award.inc.php';
    break;
  case 'fedbid_award':
    $channel = 'FedBid';
    include_once 'plantillas/awards/fedbid_award.inc.php';
    break;
  case 'emails_award':
    $channel = 'E-mails';
    include_once 'plantillas/awards/emails_award.inc.php';
    break;
  case 'mailbox_award':
    $channel = 'Mailbox';
    include_once 'plantillas/awards/mailbox_award.inc.php';
    break;
  case 'findfrp_award':
    $channel = 'FindFRP';
    include_once 'plantillas/awards/findfrp_award.inc.php';
    break;
  case 'embassies_award':
    $channel = 'Embassies';
    include_once 'plantillas/awards/embassies_award.inc.php';
    break;
  case 'fbo_award':
    $channel = 'FBO';
    include_once 'plantillas/awards/fbo_award.inc.php';
    break;
  case 'chemonics_award':
    $channel = 'Chemonics';
    include_once 'plantillas/awards/chemonics_award.inc.php';
    break;
  case 'ebay_amazon_award':
    $channel = 'Ebay & Amazon';
    include_once 'plantillas/awards/ebay_amazon_award.inc.php';
    break;
}
?>
