<?php
if($cotizacion_recuperada-> obtener_canal() != 'Chemonics'){
  if($cotizacion_recuperada-> obtener_completado()){
    ?>
    <a id="copy_quote" class="btn btn-primary" href="<?php echo COPY_QUOTE . $cotizacion_recuperada-> obtener_id(); ?>"><i class="fa fa-copy"></i> Copy</a>
    <?php
  }
}
?>
