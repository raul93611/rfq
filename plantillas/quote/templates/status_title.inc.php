<?php
if($cotizacion_recuperada-> obtener_invoice()){
  ?>
  <h1 class="float-right text-success"><i class="fa fa-check"></i> Invoice</h1>
  <?php
}else if($cotizacion_recuperada-> obtener_fullfillment()){
  ?>
  <h1 class="float-right text-success"><i class="fa fa-check"></i> Fulfillment</h1>
  <div class="clearfix"></div>
  <a href="<?php echo REMOVE_FULFILLMENT . $cotizacion_recuperada-> obtener_id(); ?>" class=" float-right d-block"><i class="fas fa-times"></i> Remove Fulfillment</a>
  <?php
}else if($cotizacion_recuperada-> obtener_award()){
?>
  <h1 class="float-right text-success"><i class="fa fa-check"></i> Award</h1>
  <div class="clearfix"></div>
  <a href="<?php echo REMOVE_AWARD . $cotizacion_recuperada-> obtener_id(); ?>" class=" float-right d-block"><i class="fas fa-times"></i> Remove Award</a>
<?php
}else if($cotizacion_recuperada-> obtener_status()){
?>
  <h1 class="float-right text-success"><i class="fa fa-check"></i> Submitted</h1>
<?php
}else if($cotizacion_recuperada-> obtener_completado()){
  ?>
  <h1 class="float-right text-success"><i class="fa fa-check"></i> Completed</h1>
  <?php
}
?>
