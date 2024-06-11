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
      <a href="<?= FULFILLMENT_QUOTES; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) == 'fulfillment_quotes' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Fulfillment
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= PERSONNEL_CALENDAR; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) == 'personnel_calendar' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-calendar"></i>
        <p>
          Personnel Calendar
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= PERSONNEL; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) == 'personnel' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-address-book"></i>
        <p>
          Personnel
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= TYPE_OF_PROJECT; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) == 'type_of_project' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-address-book"></i>
        <p>
          Types of Projects
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= PROVIDERS; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) == 'providers' ? 'active' : ''; ?>">
        <i class="nav-icon far fa-address-book"></i>
        <p>
          Providers
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= PAYMENT_TERMS; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) == 'payment_terms' ? 'active' : ''; ?>">
        <i class="nav-icon far fa-address-book"></i>
        <p>
          Payment Terms
        </p>
      </a>
    </li>
  </ul>
</li>