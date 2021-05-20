<?php
switch($_SESSION['role']){
  case 1:
    include_once 'plantillas/utilities/muro_admin.inc.php';
    break;
  default :
    include_once 'plantillas/utilities/muro_default.inc.php';
    break;
}
?>
