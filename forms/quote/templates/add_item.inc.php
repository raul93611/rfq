<?php if ($cotizacion_recuperada->obtener_canal() !== 'Chemonics'): ?>
  <a class="btn btn-secondary" href="<?= ADD_ITEM . '/' . $cotizacion_recuperada->obtener_id(); ?>">
    <i class="fa fa-plus-circle"></i> Add item
  </a>
<?php endif; ?>