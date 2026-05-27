<aside class="main-sidebar sidebar-dark-primary">
  <a href="<?= PERFIL ?>" class="brand-link">
    <img src="<?= RUTA_IMG ?>eP_perfil.png" alt="E-logic" style="width:32px;height:32px;border-radius:6px;opacity:0.85;margin-right:0.75rem;flex-shrink:0;">
    <span class="brand-text">E-logic</span>
  </a>
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= RUTA_IMG ?>user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= $_SESSION['user']->obtener_nombre_usuario() ?></a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php
        $sidebar_sections = [
          'charts_sidebar.inc.php',
          'search_quotes_sidebar.inc.php',
          'users_sidebar.inc.php',
          'sales_sidebar.inc.php',
          'fulfillment_sidebar.inc.php',
          'accounting_sidebar.inc.php',
          'projections_sidebar.inc.php',
          'employee_docs_sidebar.inc.php',
          'reports_sidebar.inc.php',
        ];

        foreach ($sidebar_sections as $section) {
          include_once $section;
        }
        ?>

        <!-- My Account — always visible at bottom of nav -->
        <li class="nav-item" style="margin-top:auto;border-top:1px solid rgba(255,255,255,0.08);padding-top:6px;">
          <a href="<?= MY_ACCOUNT ?>" class="nav-link <?= ($partes_ruta[2] ?? null) == 'account' ? 'active' : ''; ?>">
            <i class="fas fa-user-circle nav-icon"></i>
            <p>My Account</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>