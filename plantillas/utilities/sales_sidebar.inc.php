<li class="nav-item 
<?php
echo ($gestor_actual == 'cotizaciones' ||
  $gestor_actual == 'completados' ||
  $gestor_actual == 'submitted' ||
  $gestor_actual == 'award' ||
  $gestor_actual == 'no_bid' ||
  $gestor_actual == 'no_submitted' ||
  $gestor_actual == 'cancelled') &&
  $cotizacion != 'editar_cotizacion' &&
  $cotizacion != 'add_item' &&
  $cotizacion != 'add_provider' &&
  $cotizacion != 'edit_item' &&
  $cotizacion != 'edit_provider' ? 'menu-open' : '';
?>">
  <a href="#" class="nav-link 
  <?php
  echo ($gestor_actual == 'cotizaciones' ||
    $gestor_actual == 'completados' ||
    $gestor_actual == 'submitted' ||
    $gestor_actual == 'award' ||
    $gestor_actual == 'no_bid' ||
    $gestor_actual == 'no_submitted' ||
    $gestor_actual == 'cancelled') &&
    $cotizacion != 'editar_cotizacion' &&
    $cotizacion != 'add_item' &&
    $cotizacion != 'add_provider' &&
    $cotizacion != 'edit_item' &&
    $cotizacion != 'edit_provider' ? 'active' : '';
  ?>">
    <i class="nav-icon fas fa-tag"></i>
    <p>
      Sales
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
  <li class="nav-item">
      <a href="<?php echo NUEVA_COTIZACION; ?>" class="nav-link <?php echo $cotizacion == 'nuevo' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-plus"></i>
        <p>
          New quote
        </p>
      </a>
    </li>
    <li class="nav-item <?php echo $gestor_actual == 'cotizaciones' && $cotizacion != 'editar_cotizacion' && $cotizacion != 'nuevo' && $cotizacion != 'add_item' && $cotizacion != 'add_provider' && $cotizacion != 'edit_item' && $cotizacion != 'edit_provider' ? 'menu-open' : ''; ?>">
      <a href="#" class="nav-link <?php echo $gestor_actual == 'cotizaciones' && $cotizacion != 'editar_cotizacion' && $cotizacion != 'nuevo' && $cotizacion != 'add_item' && $cotizacion != 'add_provider' && $cotizacion != 'edit_item' && $cotizacion != 'edit_provider' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-tag"></i>
        <p>
          Quotes
          <i class="right fa fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo GSA_BUY; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>GSA-Buy</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FEDBID; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Unison</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMAILS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>E-mails</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo MAILBOX; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Mailbox</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FINDFRP; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>FindFRP</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMBASSIES; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Embassies</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FBO; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>SAM</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo SEAPORT; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Seaport</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo CHEMONICS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Chemonics</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EBAY_AMAZON; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Ebay & Amazon</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo STARSIII; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Stars III</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item <?php echo $gestor_actual == 'completados' ? 'menu-open' : ''; ?>">
      <a href="#" class="nav-link <?php echo $gestor_actual == 'completados' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-tag"></i>
        <p>
          Completed
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo GSA_BUY_COMPLETADOS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>GSA-Buy</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FEDBID_COMPLETADOS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Unison</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMAILS_COMPLETADOS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>E-mails</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo MAILBOX_COMPLETADOS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Mailbox</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FINDFRP_COMPLETADOS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>FindFRP</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMBASSIES_COMPLETADOS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Embassies</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FBO_COMPLETADOS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>SAM</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo SEAPORT_COMPLETADOS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Seaport</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo STARSIII_COMPLETADOS; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Stars III</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item <?php echo $gestor_actual == 'submitted' ? 'menu-open' : ''; ?>">
      <a href="#" class="nav-link <?php echo $gestor_actual == 'submitted' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-tag"></i>
        <p>
          Submitted
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo GSA_BUY_SUBMITTED; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>GSA-Buy</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FEDBID_SUBMITTED; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Unison</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMAILS_SUBMITTED; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>E-mails</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo MAILBOX_SUBMITTED; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Mailbox</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FINDFRP_SUBMITTED; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>FindFRP</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMBASSIES_SUBMITTED; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Embassies</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FBO_SUBMITTED; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>SAM</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo SEAPORT_SUBMITTED; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Seaport</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo STARSIII_SUBMITTED; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Stars III</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item <?php echo $gestor_actual == 'award' ? 'menu-open' : ''; ?>">
      <a href="#" class="nav-link <?php echo $gestor_actual == 'award' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-tag"></i>
        <p>
          Award
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo GSA_BUY_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>GSA-Buy</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FEDBID_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Unison</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMAILS_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>E-mails</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo MAILBOX_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Mailbox</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FINDFRP_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>FindFRP</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMBASSIES_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Embassies</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FBO_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>SAM</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo SEAPORT_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Seaport</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo CHEMONICS_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Chemonics</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EBAY_AMAZON_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Ebay & Amazon</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo STARSIII_AWARD; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Stars III</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="<?php echo NO_BID; ?>" class="nav-link <?php echo $gestor_actual == 'no_bid' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          No Bid
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo NO_SUBMITTED; ?>" class="nav-link <?php echo $gestor_actual == 'no_submitted' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Not submitted
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo CANCELLED; ?>" class="nav-link <?php echo $gestor_actual == 'cancelled' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Cancelled
        </p>
      </a>
    </li>
  </ul>
</li>