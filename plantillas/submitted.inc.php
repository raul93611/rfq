<?php

switch ($cotizacion) {
    case 'gsa_buy_submitted':
        $canal = 'GSA-Buy';
        include_once 'plantillas/gsa_buy_submitted.inc.php';
        break;
    case 'fedbid_submitted':
        $canal = 'FedBid';
        include_once 'plantillas/fedbid_submitted.inc.php';
        break;
    case 'emails_submitted':
        $canal = 'E-mails';
        include_once 'plantillas/emails_submitted.inc.php';
        break;
    case 'findfrp_submitted':
        $canal = 'FindFRP';
        include_once 'plantillas/findfrp_submitted.inc.php';
        break;
    case 'embassies_submitted':
        $canal = 'Embassies';
        include_once 'plantillas/embassies_submitted.inc.php';
        break;
    case 'fbo_submitted':
        $canal = 'FBO';
        include_once 'plantillas/fbo_submitted.inc.php';
        break;
}
?>


