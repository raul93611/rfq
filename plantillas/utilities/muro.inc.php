<?php
switch($_SESSION['cargo']){
  case 1:
    include_once 'plantillas/utilities/muro_admin.inc.php';
    break;
  default :
    include_once 'plantillas/utilities/muro_default.inc.php';
    break;
}
?>