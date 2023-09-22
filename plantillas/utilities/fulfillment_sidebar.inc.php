<li class="nav-item <?= ($partes_ruta[2] ?? null) == 'fulfillment' ? 'menu-open' : ''; ?>">
  <a href="#" class="nav-link <?= ($partes_ruta[2] ?? null) == 'fulfillment' ? 'active' : ''; ?>">
    <i class="nav-icon fas fa-tag"></i>
    <p>
      Fulfillment
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?= FULFILLMENT_QUOTES; ?>" class="nav-link <?= $partes_ruta[3] == 'fulfillment_quotes' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Fulfillment
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= WEEKLY_PROJECTIONS; ?>" class="nav-link <?= $partes_ruta[3] == 'weekly_projections' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Weekly Projections 2022
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
      <a href="<?= PERSONNEL_CALENDAR; ?>" class="nav-link <?= $partes_ruta[3] == 'personnel_calendar' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-calendar"></i>
        <p>
          Personnel Calendar
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= PERSONNEL; ?>" class="nav-link <?= $partes_ruta[3] == 'personnel' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-address-book"></i>
        <p>
          Personnel
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= TYPE_OF_PROJECT; ?>" class="nav-link <?= $partes_ruta[3] == 'type_of_project' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-address-book"></i>
        <p>
          Types of Projects
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= PROVIDERS; ?>" class="nav-link <?= $partes_ruta[3] == 'providers' ? 'active' : ''; ?>">
        <i class="nav-icon far fa-address-book"></i>
        <p>
          Providers
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= PAYMENT_TERMS; ?>" class="nav-link <?= $partes_ruta[3] == 'payment_terms' ? 'active' : ''; ?>">
        <i class="nav-icon far fa-address-book"></i>
        <p>
          Payment Terms
        </p>
      </a>
    </li>
  </ul>
</li>