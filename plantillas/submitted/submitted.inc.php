<?php
$canal = Input::inverse_translate_channel(substr($cotizacion, 0, -10));
include_once $cotizacion . '.inc.php';
?>
