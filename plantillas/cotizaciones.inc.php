<?php

switch ($cotizacion) {
    case 'gsa_buy':
        $canal = 'GSA-Buy';
        include_once 'plantillas/gsa_buy.inc.php';
        break;
    case 'fedbid':
        $canal = 'FedBid';
        include_once 'plantillas/fedbid.inc.php';
        break;
    case 'emails':
        $canal = 'E-mails';
        include_once 'plantillas/emails.inc.php';
        break;
    case 'findfrp':
        $canal = 'FindFRP';
        include_once 'plantillas/findfrp.inc.php';
        break;
    case 'embassies':
        $canal = 'Embassies';
        include_once 'plantillas/embassies.inc.php';
        break;
    case 'fbo':
        $canal = 'FBO';
        include_once 'plantillas/fbo.inc.php';
        break;
}
?>
