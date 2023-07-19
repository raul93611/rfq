<li class="nav-item 
<?php
echo ($partes_ruta[2] ?? null) == 'quote' ? 'menu-open' : '';
?>">
  <a href="#" class="nav-link 
  <?php
  echo ($partes_ruta[2] ?? null) == 'quote' ? 'active' : '';
  ?>">
    <i class="nav-icon fas fa-tag"></i>
    <p>
      Sales
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
  <li class="nav-item">
      <a href="<?php echo NUEVA_COTIZACION; ?>" class="nav-link <?php echo ($partes_ruta[3] ?? null) == 'nuevo' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-plus"></i>
        <p>
          New quote
        </p>
      </a>
    </li>
    <li class="nav-item <?php echo ($partes_ruta[3] ?? null) == 'channel' ? 'menu-open' : ''; ?>">
      <a href="#" class="nav-link <?php echo ($partes_ruta[3] ?? null) == 'channel' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-tag"></i>
        <p>
          Quotes
          <i class="right fa fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo GSA_BUY; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'GSA-Buy' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>GSA-Buy</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FEDBID; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FedBid' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Unison</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMAILS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'E-mails' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>E-mails</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo MAILBOX; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Mailbox' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Mailbox</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FINDFRP; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FindFRP' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>FindFRP</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMBASSIES; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Embassies' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Embassies</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FBO; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FBO' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>SAM</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo SEAPORT; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Seaport' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Seaport</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo CHEMONICS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Chemonics' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Chemonics</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EBAY_AMAZON; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Ebay%20&%20Amazon' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Ebay & Amazon</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo STARSIII; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Stars%20III' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Stars III</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item <?php echo ($partes_ruta[3] ?? null) == 'completed' ? 'menu-open' : ''; ?>">
      <a href="#" class="nav-link <?php echo ($partes_ruta[3] ?? null) == 'completed' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-tag"></i>
        <p>
          Completed
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo GSA_BUY_COMPLETADOS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'GSA-Buy' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>GSA-Buy</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FEDBID_COMPLETADOS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FedBid' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Unison</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMAILS_COMPLETADOS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'E-mails' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>E-mails</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo MAILBOX_COMPLETADOS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Mailbox' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Mailbox</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FINDFRP_COMPLETADOS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FindFRP' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>FindFRP</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMBASSIES_COMPLETADOS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Embassies' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Embassies</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FBO_COMPLETADOS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FBO' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>SAM</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo SEAPORT_COMPLETADOS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Seaport' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Seaport</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EBAY_AMAZON_COMPLETADOS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Ebay%20&%20Amazon' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Ebay & Amazon</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo STARSIII_COMPLETADOS; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Stars%20III' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Stars III</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item <?php echo ($partes_ruta[3] ?? null) == 'submitted' ? 'menu-open' : ''; ?>">
      <a href="#" class="nav-link <?php echo ($partes_ruta[3] ?? null) == 'submitted' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-tag"></i>
        <p>
          Submitted
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo GSA_BUY_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'GSA-Buy' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>GSA-Buy</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FEDBID_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FedBid' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Unison</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMAILS_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'E-mails' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>E-mails</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo MAILBOX_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Mailbox' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Mailbox</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FINDFRP_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FindFRP' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>FindFRP</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMBASSIES_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Embassies' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Embassies</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FBO_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FBO' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>SAM</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo SEAPORT_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Seaport' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Seaport</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EBAY_AMAZON_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Ebay%20&%20Amazon' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Ebay & Amazon</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo STARSIII_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Stars%20III' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Stars III</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item <?php echo ($partes_ruta[3] ?? null) == 'award' ? 'menu-open' : ''; ?>">
      <a href="#" class="nav-link <?php echo ($partes_ruta[3] ?? null) == 'award' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-tag"></i>
        <p>
          Award
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo GSA_BUY_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'GSA-Buy' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>GSA-Buy</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FEDBID_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FedBid' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Unison</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMAILS_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'E-mails' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>E-mails</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo MAILBOX_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Mailbox' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Mailbox</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FINDFRP_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FindFRP' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>FindFRP</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EMBASSIES_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Embassies' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Embassies</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo FBO_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'FBO' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>SAM</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo SEAPORT_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Seaport' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Seaport</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo CHEMONICS_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Chemonics' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Chemonics</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo EBAY_AMAZON_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Ebay%20&%20Amazon' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Ebay & Amazon</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo STARSIII_AWARD; ?>" class="nav-link <?php echo ($partes_ruta[4] ?? null) == 'Stars%20III' ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Stars III</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="<?php echo NO_BID; ?>" class="nav-link <?php echo ($partes_ruta[3] ?? null) == 'no_bid' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          No Bid
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo NO_SUBMITTED; ?>" class="nav-link <?php echo ($partes_ruta[3] ?? null) == 'no_submitted' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Not submitted
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo CANCELLED; ?>" class="nav-link <?php echo ($partes_ruta[3] ?? null) == 'cancelled' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Cancelled
        </p>
      </a>
    </li>
  </ul>
</li>