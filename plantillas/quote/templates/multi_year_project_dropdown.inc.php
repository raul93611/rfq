<?php
// Check if the project is a multi-year master or has slaves
$multiYearProject = $cotizacion_recuperada->obtener_multi_year_project();
$slavesQuotes = $cotizacion_recuperada->getSlavesQuotes();

// Display master project if exists
if ($multiYearProject) {
?>
  <small>
    <a class="btn btn-link" href="<?= EDITAR_COTIZACION . '/' . $multiYearProject; ?>">
      Master: <?= $multiYearProject; ?>
    </a>
    <form class="d-inline" action="<?= REMOVE_MASTER; ?>" method="post">
      <input type="hidden" name="slave" value="<?= $cotizacion_recuperada->obtener_id(); ?>">
      <input type="submit" name="send" class="border-0 p-0 m-0 bg-transparent text-danger" value="&times;">
    </form>
  </small>
<?php
}
// Display slave quotes if any
elseif ($slavesQuotes) {
?>
  <div class="btn-group">
    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Slaves
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <?php foreach ($slavesQuotes as $slaveQuote): ?>
        <li class="dropdown-item">
          <a href="<?= EDITAR_COTIZACION . '/' . $slaveQuote['id']; ?>">
            <?= $slaveQuote['id']; ?>
          </a>
          <form class="float-right" action="<?= REMOVE_SLAVE; ?>" method="post">
            <input type="hidden" name="master" value="<?= $cotizacion_recuperada->obtener_id(); ?>">
            <input type="hidden" name="slave" value="<?= $slaveQuote['id']; ?>">
            <input type="submit" name="send" class="border-0 p-0 m-0 bg-transparent text-danger" value="&times;">
          </form>
        </li>
      <?php endforeach; ?>
    </div>
  </div>
<?php
}
?>