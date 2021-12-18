<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?php echo PERFIL; ?>" class="brand-link">
    <img src="<?php echo RUTA_IMG; ?>eP_perfil.png" alt="E-logic" style="width:35px;height:35px;" class="brand-image img-thumbnail elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">E-logic</span>
  </a>
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo RUTA_IMG; ?>user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo $_SESSION['nombre_usuario']; ?></a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item <?php echo $gestor_actual == '' || $gestor_actual == 'my_tasks' || $gestor_actual == 'tasks_done' ? 'menu-open' : ''; ?>">
          <a href="#" class="nav-link <?php echo $gestor_actual == '' || $gestor_actual == 'my_tasks' || $gestor_actual == 'tasks_done' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-thumbtack"></i>
            <p>
              Tasks
              <i class="right fa fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo PERFIL; ?>" class="nav-link <?php echo $gestor_actual == '' ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>All Tasks</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo MY_TASKS; ?>" class="nav-link <?php echo $gestor_actual == 'my_tasks' ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>My Tasks</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo TASKS_DONE; ?>" class="nav-link <?php echo $gestor_actual == 'tasks_done' ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Tasks Done</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview menu-open">
          <a href="<?php echo CHARTS; ?>" class="nav-link <?php echo $gestor_actual == 'charts' ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie nav-icon"></i>
            <p>Charts</p>
          </a>
        </li>
        <?php
        if ($_SESSION['cargo'] == 1) {
        ?>
        <li class="nav-item has-treeview menu-open">
          <a href="<?php echo REGISTRO; ?>" class="nav-link <?php echo $gestor_actual == 'registro' || $gestor_actual == 'registro_correcto' ? 'active' : ''; ?>">
            <i class="fa fa-users nav-icon"></i>
            <p>User register</p>
          </a>
        </li>
        <?php
        }
        ?>
        <li class="nav-item">
          <a href="<?php echo NUEVA_COTIZACION; ?>" class="nav-link <?php echo $cotizacion == 'nuevo' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-plus"></i>
            <p>
              New quote
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo SEARCH_QUOTES; ?>" class="nav-link <?php echo $gestor_actual == 'search_quotes' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-search"></i>
            <p>
              Search
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
          <a href="<?php echo FULFILLMENT_QUOTES; ?>" class="nav-link <?php echo $gestor_actual == 'fulfillment_quotes' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Fulfillment Quotes
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo INVOICE_QUOTES; ?>" class="nav-link <?php echo $gestor_actual == 'invoice_quotes' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Invoice Quotes
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo SUBMITTED_INVOICE_QUOTES; ?>" class="nav-link <?php echo $gestor_actual == 'submitted_invoice_quotes' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Submitted Invoice Quotes
            </p>
          </a>
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
              No submitted
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
        <li class="nav-item">
          <a href="<?php echo EMPLOYEE_DOCS_PAGE; ?>" class="nav-link <?php echo $gestor_actual == 'employee_docs_page' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-file"></i>
            <p>
              Employee docs
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo REPORTS; ?>" class="nav-link <?php echo $gestor_actual == 'reports' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-file"></i>
            <p>
              Reports
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
    </nav>
  </div>
</aside>
