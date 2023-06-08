<?php
if($cotizacion_recuperada-> obtener_multi_year_project()){
  ?>
  <small>
    <a class="btn btn-link" href="<?php echo EDITAR_COTIZACION . '/' . $cotizacion_recuperada-> obtener_multi_year_project(); ?>">Master: <?php echo $cotizacion_recuperada-> obtener_multi_year_project(); ?></a>
    <form class="d-inline" action="<?php echo REMOVE_MASTER; ?>" method="post">
      <input type="hidden" name="slave" value="<?php echo $cotizacion_recuperada-> obtener_id(); ?>">
      <input class="border-0 p-0 m-0 bg-transparent text-danger" type="submit" name="send" value="&times;">
    </form>
  </small>
  <?php
}else if($cotizacion_recuperada-> getSlavesQuotes()){
  ?>
  <div class="btn-group">
    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Slaves
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <?php
      foreach ($cotizacion_recuperada-> getSlavesQuotes() as $key => $slave_quote) {
        ?>
        <li class="dropdown-item">
          <a href="<?php echo EDITAR_COTIZACION . '/' . $slave_quote['id']; ?>"><?php echo $slave_quote['id']; ?></a>
          <form class="float-right" action="<?php echo REMOVE_SLAVE; ?>" method="post">
            <input type="hidden" name="master" value="<?php echo $cotizacion_recuperada-> obtener_id(); ?>">
            <input type="hidden" name="slave" value="<?php echo $slave_quote['id']; ?>">
            <input class="border-0 p-0 m-0 bg-transparent text-danger" type="submit" name="send" value="&times;">
          </form>
        </li>
        <?php
      }
      ?>
    </div>
  </div>
  <?php
}
?>
