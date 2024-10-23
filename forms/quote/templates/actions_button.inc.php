<div class="btn-group dropup">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Actions
  </button>
  <div class="dropdown-menu">
    <?php if ($cotizacion_recuperada->obtener_fullfillment() && $re_quote_exists): ?>
      <a class="dropdown-item" href="<?= TRACKING . $cotizacion_recuperada->obtener_id(); ?>">Tracking</a>
    <?php endif; ?>

    <?php if ($cotizacion_recuperada->obtener_canal() !== 'Chemonics' && $cotizacion_recuperada->obtener_award()): ?>
      <a class="dropdown-item" href="<?= RE_QUOTE . $cotizacion_recuperada->obtener_id(); ?>">Re-quote</a>
    <?php endif; ?>

    <?php if ($cotizacion_recuperada->obtener_fullfillment()): ?>
      <a class="dropdown-item" href="<?= FULFILLMENT . $cotizacion_recuperada->obtener_id(); ?>">Fulfillment</a>
      <a class="dropdown-item" href="<?= KPI . $cotizacion_recuperada->obtener_id(); ?>">KPI</a>
    <?php endif; ?>
  </div>
</div>