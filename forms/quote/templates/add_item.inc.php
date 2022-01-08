<?php
if($cotizacion_recuperada-> obtener_canal() != 'Chemonics' && $cotizacion_recuperada-> obtener_canal() != 'Ebay & Amazon'){
  ?>
  <a class="btn btn-primary add_item_charter" href="<?php echo ADD_ITEM . '/' . $cotizacion_recuperada->obtener_id(); ?>"><i class="fa fa-plus-circle"></i> Add item</a>
  <?php
}
?>
