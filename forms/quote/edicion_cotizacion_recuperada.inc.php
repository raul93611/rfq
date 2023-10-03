<input type="hidden" name="id_rfq" value="<?= $cotizacion_recuperada->obtener_id(); ?>">
<?php
RepositorioItem::escribir_items($cotizacion_recuperada->obtener_id());
if ($cotizacion_recuperada->isServices()) {
  include_once 'plantillas/services/services.inc.php';
}
?>