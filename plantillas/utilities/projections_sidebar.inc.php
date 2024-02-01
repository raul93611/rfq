<li class="nav-item <?= ($partes_ruta[2] ?? null) == 'proejction' ? 'menu-open' : ''; ?>">
  <a href="#" class="nav-link <?= ($partes_ruta[2] ?? null) == 'projection' ? 'active' : ''; ?>">
    <i class="nav-icon fas fa-chart-line"></i>
    <p>
      Daily Projections
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?= DAILY; ?>" class="nav-link <?= $partes_ruta[3] == 'daily' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Daily
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= WEEKLY_PROJECTIONS_2023; ?>" class="nav-link <?= $partes_ruta[3] == 'weekly_projections_2023' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Weekly Projections 2023
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= WEEKLY_PROJECTIONS_2022; ?>" class="nav-link <?= $partes_ruta[3] == 'weekly_projections_2022' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Weekly Projections 2022
        </p>
      </a>
    </li>
  </ul>
</li>