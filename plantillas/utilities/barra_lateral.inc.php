<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?= PERFIL ?>" class="brand-link">
    <img src="<?= RUTA_IMG ?>eP_perfil.png" alt="E-logic" class="brand-image img-thumbnail elevation-3" style="width:35px;height:35px;opacity:.8">
    <span class="brand-text font-weight-light">E-logic</span>
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
          'tasks_sidebar.inc.php',
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
      </ul>
    </nav>
  </div>
</aside>