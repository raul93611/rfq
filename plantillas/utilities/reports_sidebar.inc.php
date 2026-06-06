<?php $reportsRoute = $partes_ruta[3] ?? null; ?>
<li class="nav-item">
  <a href="<?= REPORTS_TABLES; ?>" class="nav-link <?= $reportsRoute === 'reports_tables' ? 'active' : ''; ?>">
    <i class="nav-icon fas fa-file-alt"></i>
    <p>Reports</p>
  </a>
</li>
<li class="nav-item">
  <a href="<?= PIPELINE_METRICS; ?>" class="nav-link <?= $reportsRoute === 'pipeline_metrics' ? 'active' : ''; ?>">
    <i class="nav-icon fas fa-chart-pie"></i>
    <p>Bid Pipeline Metrics</p>
  </a>
</li>
