<li class="nav-item 
<?php
echo $gestor_actual == 'fulfillment_quotes' ||
  $gestor_actual == 'weekly_projections' ||
  $gestor_actual == 'payment_terms' ||
  $gestor_actual == 'providers' ? 'menu-open' : '';
?>">
  <a href="#" class="nav-link 
  <?php 
  echo $gestor_actual == 'fulfillment_quotes' || 
  $gestor_actual == 'weekly_projections' || 
  $gestor_actual == 'payment_terms' || 
  $gestor_actual == 'providers' ? 'active' : ''; 
  ?>">
    <i class="nav-icon fas fa-tag"></i>
    <p>
      Fulfillment
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo FULFILLMENT_QUOTES; ?>" class="nav-link <?php echo $gestor_actual == 'fulfillment_quotes' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Fulfillment
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo WEEKLY_PROJECTIONS; ?>" class="nav-link <?php echo $gestor_actual == 'weekly_projections' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Weekly Projections
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo PROVIDERS; ?>" class="nav-link <?php echo $gestor_actual == 'providers' ? 'active' : ''; ?>">
        <i class="nav-icon far fa-address-book"></i>
        <p>
          Providers
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo PAYMENT_TERMS; ?>" class="nav-link <?php echo $gestor_actual == 'payment_terms' ? 'active' : ''; ?>">
        <i class="nav-icon far fa-address-book"></i>
        <p>
          Payment Terms
        </p>
      </a>
    </li>
  </ul>
</li>