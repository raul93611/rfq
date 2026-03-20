<?php
$multiYearProject = $cotizacion_recuperada->obtener_multi_year_project();
$slavesQuotes     = $cotizacion_recuperada->getSlavesQuotes();

if ($multiYearProject) : ?>

  <div style="display:inline-flex;align-items:center;gap:5px;background:#f0f7ff;border:1px solid #b8d9f5;border-radius:20px;padding:3px 10px 3px 8px;">
    <i class="fas fa-link" style="font-size:10px;color:#13A8F0;"></i>
    <a href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($multiYearProject, ENT_QUOTES, 'UTF-8'); ?>"
       style="font-size:12px;font-weight:600;color:#13A8F0;text-decoration:none;">
      Master: <?= htmlspecialchars($multiYearProject, ENT_QUOTES, 'UTF-8'); ?>
    </a>
    <form class="d-inline m-0" action="<?= REMOVE_MASTER; ?>" method="post">
      <input type="hidden" name="slave" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_id(), ENT_QUOTES, 'UTF-8'); ?>">
      <button type="submit" name="send" class="btn btn-link p-0 m-0" style="font-size:18px;color:#e74c3c;line-height:1;vertical-align:middle;">&times;</button>
    </form>
  </div>

<?php elseif ($slavesQuotes) : ?>

  <div class="dropdown">
    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-sitemap mr-1"></i> Linked Years
    </button>
    <div class="dropdown-menu">
      <?php foreach ($slavesQuotes as $slaveQuote) : ?>
        <div class="dropdown-item d-flex align-items-center justify-content-between" style="gap:12px;">
          <a href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($slaveQuote['id'], ENT_QUOTES, 'UTF-8'); ?>"
             style="font-size:13px;color:#39485a;text-decoration:none;">
            Proposal #<?= htmlspecialchars($slaveQuote['id'], ENT_QUOTES, 'UTF-8'); ?>
          </a>
          <form class="d-inline m-0" action="<?= REMOVE_SLAVE; ?>" method="post">
            <input type="hidden" name="master" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_id(), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="slave" value="<?= htmlspecialchars($slaveQuote['id'], ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" name="send" class="btn btn-link p-0 m-0" style="font-size:18px;color:#e74c3c;line-height:1;">&times;</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

<?php endif; ?>