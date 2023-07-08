<li class="nav-item 
<?php
echo ($partes_ruta[2] ?? null) == 'fulfillment' ? 'menu-open' : '';
?>">
  <a href="#" class="nav-link 
  <?php 
  echo ($partes_ruta[2] ?? null) == 'fulfillment' ? 'active' : ''; 
  ?>">
    <i class="nav-icon fas fa-tag"></i>
    <p>
      Fulfillment
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo FULFILLMENT_QUOTES; ?>" class="nav-link <?php echo $partes_ruta[3] == 'fulfillment_quotes' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Fulfillment
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo WEEKLY_PROJECTIONS; ?>" class="nav-link <?php echo $partes_ruta[3] == 'weekly_projections' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Weekly Projections 2022
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo WEEKLY_PROJECTIONS_2023; ?>" class="nav-link <?php echo $partes_ruta[3] == 'weekly_projections_2023' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Weekly Projections 2023
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo PROVIDERS; ?>" class="nav-link <?php echo $partes_ruta[3] == 'providers' ? 'active' : ''; ?>">
        <i class="nav-icon far fa-address-book"></i>
        <p>
          Providers
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo PAYMENT_TERMS; ?>" class="nav-link <?php echo $partes_ruta[3] == 'payment_terms' ? 'active' : ''; ?>">
        <i class="nav-icon far fa-address-book"></i>
        <p>
          Payment Terms
        </p>
      </a>
    </li>
  </ul>
</li>