<div class="btn-group dropup">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Actions
  </button>
  <div class="dropdown-menu">
    <?php
    if($cotizacion_recuperada-> obtener_fullfillment() && $re_quote_exists){
      ?>
      <a class="dropdown-item" href="<?php echo TRACKING . $cotizacion_recuperada-> obtener_id(); ?>">Tracking</a>
      <?php
    }
    if($cotizacion_recuperada-> obtener_canal() != 'Chemonics'){
      if($cotizacion_recuperada-> obtener_award() && $items_exists){
        ?>
        <a href="<?php echo RE_QUOTE . $cotizacion_recuperada-> obtener_id(); ?>" class="dropdown-item">Re-quote</a>
        <?php
      }
    }
    if($cotizacion_recuperada-> obtener_fullfillment()){
      ?>
      <a href="<?php echo FULFILLMENT . $cotizacion_recuperada-> obtener_id(); ?>" class="dropdown-item">Fulfillment</a>
      <a href="<?php echo KPI . $cotizacion_recuperada-> obtener_id(); ?>" class="dropdown-item">KPI</a>
      <?php
    }
    ?>
  </div>
</div>
  
