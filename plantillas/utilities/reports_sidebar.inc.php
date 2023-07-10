<li class="nav-item <?php echo ($partes_ruta[2] ?? null) == 'reports' ? 'menu-open' : ''; ?>">
  <a href="#" class="nav-link <?php echo ($partes_ruta[2] ?? null) == 'reports' ? 'active' : ''; ?>">
    <i class="nav-icon fas fa-flag"></i>
    <p>
      Reports
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo REPORTS_TABLES; ?>" class="nav-link <?php echo ($partes_ruta[3] ?? null) == 'reports_tables' ? 'active' : ''; ?>">
        <i class="far fa-circle nav-icon"></i>
        <p>Tables</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo REPORTS_CHARTS; ?>" class="nav-link <?php echo ($partes_ruta[3] ?? null) == 'reports_charts' ? 'active' : ''; ?>">
        <i class="far fa-circle nav-icon"></i>
        <p>Charts</p>
      </a>
    </li>
  </ul>
</li>