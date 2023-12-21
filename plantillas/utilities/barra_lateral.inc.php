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
        <a href="#" class="d-block"><?php echo $_SESSION['user']->obtener_nombre_usuario(); ?></a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php
        include_once 'tasks_sidebar.inc.php';
        include_once 'charts_sidebar.inc.php';
        include_once 'search_quotes_sidebar.inc.php';
        include_once 'users_sidebar.inc.php';
        include_once 'sales_sidebar.inc.php';
        include_once 'fulfillment_sidebar.inc.php';
        include_once 'accounting_sidebar.inc.php';
        include_once 'projections_sidebar.inc.php';
        include_once 'employee_docs_sidebar.inc.php';
        include_once 'reports_sidebar.inc.php';
        ?>
      </ul>
    </nav>
  </div>
</aside>