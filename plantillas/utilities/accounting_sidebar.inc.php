<li class="nav-item 
<?php
echo $partes_ruta[2] == 'accounting' ? 'menu-open' : '';
?>">
  <a href="#" class="nav-link 
  <?php
  echo $partes_ruta[2] == 'accounting' ? 'active' : '';
  ?>">
    <i class="nav-icon fas fa-tag"></i>
    <p>
      Accounting
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo INVOICE_QUOTES; ?>" class="nav-link <?php echo $partes_ruta[3] == 'invoice_quotes' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Invoice
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo SUBMITTED_INVOICE_QUOTES; ?>" class="nav-link <?php echo $partes_ruta[3] == 'submitted_invoice_quotes' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Submitted Invoice
        </p>
      </a>
    </li>
  </ul>
</li>