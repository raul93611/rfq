<li class="nav-item">
  <?php
  $currentRoute = $partes_ruta[2] ?? null;
  $isActive = $currentRoute === 'reports_tables';
  ?>
  <a href="<?= REPORTS_TABLES; ?>" class="nav-link <?= $isActive ? 'active' : ''; ?>">
    <i class="nav-icon fas fa-file-alt"></i>
    <p>Reports</p>
  </a>
</li>