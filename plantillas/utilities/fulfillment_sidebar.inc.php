<li class="nav-item <?= ($partes_ruta[2] ?? '') === 'fulfillment' ? 'menu-open' : ''; ?>">
  <a href="#" class="nav-link <?= ($partes_ruta[2] ?? '') === 'fulfillment' ? 'active' : ''; ?>">
    <i class="nav-icon fas fa-tag"></i>
    <p>
      Fulfillment
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <!-- Fulfillment Quotes -->
    <li class="nav-item">
      <a href="<?= FULFILLMENT_QUOTES; ?>" class="nav-link <?= ($partes_ruta[3] ?? '') === 'fulfillment_quotes' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>Fulfillment</p>
      </a>
    </li>
    <!-- Personnel Calendar -->
    <li class="nav-item">
      <a href="<?= PERSONNEL_CALENDAR; ?>" class="nav-link <?= ($partes_ruta[3] ?? '') === 'personnel_calendar' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-calendar"></i>
        <p>Personnel Calendar</p>
      </a>
    </li>
    <!-- Personnel -->
    <li class="nav-item">
      <a href="<?= PERSONNEL; ?>" class="nav-link <?= ($partes_ruta[3] ?? '') === 'personnel' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-address-book"></i>
        <p>Personnel</p>
      </a>
    </li>
    <!-- Types of Projects -->
    <li class="nav-item">
      <a href="<?= TYPE_OF_PROJECT; ?>" class="nav-link <?= ($partes_ruta[3] ?? '') === 'type_of_project' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-project-diagram"></i> <!-- Updated icon for clarity -->
        <p>Types of Projects</p>
      </a>
    </li>
    <!-- Providers -->
    <li class="nav-item">
      <a href="<?= PROVIDERS; ?>" class="nav-link <?= ($partes_ruta[3] ?? '') === 'providers' ? 'active' : ''; ?>">
        <i class="nav-icon far fa-address-book"></i>
        <p>Providers</p>
      </a>
    </li>
    <!-- Payment Terms -->
    <li class="nav-item">
      <a href="<?= PAYMENT_TERMS; ?>" class="nav-link <?= ($partes_ruta[3] ?? '') === 'payment_terms' ? 'active' : ''; ?>">
        <i class="nav-icon far fa-credit-card"></i> <!-- Updated icon for clarity -->
        <p>Payment Terms</p>
      </a>
    </li>
  </ul>
</li>