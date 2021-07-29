<?php
$canal = Input::inverse_translate_channel(substr($cotizacion, 0, -12));
include_once $cotizacion . '.inc.php';
?>
