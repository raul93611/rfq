<li class="nav-item <?= ($partes_ruta[2] ?? null) === 'accounting' ? 'menu-open' : '' ?>">
  <a href="#" class="nav-link <?= ($partes_ruta[2] ?? null) === 'accounting' ? 'active' : '' ?>">
    <i class="nav-icon fas fa-tag"></i>
    <p>
      Accounting
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?= INVOICE_QUOTES; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) === 'invoice_quotes' ? 'active' : '' ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>Invoice</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= SUBMITTED_INVOICE_QUOTES; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) === 'submitted_invoice_quotes' ? 'active' : '' ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>Submitted Invoice</p>
      </a>
    </li>
  </ul>
</li>