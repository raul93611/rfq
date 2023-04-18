<?php
if ($_SESSION['user']->is_admin()) {
?>
  <li class="nav-item <?php echo $partes_ruta[2] == 'user' ? 'menu-open' : ''; ?>">
    <a href="#" class="nav-link <?php echo $partes_ruta[2] == 'user' ? 'active' : ''; ?>">
      <i class="nav-icon fas fa-users"></i>
      <p>
        Users
        <i class="right fa fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="<?php echo USERS; ?>" class="nav-link <?php echo $partes_ruta[3] == 'users' ? 'active' : ''; ?>">
          <i class="far fa-circle nav-icon"></i>
          <p>All Users</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo REGISTRO; ?>" class="nav-link <?php echo $partes_ruta[3] == 'registro' ? 'active' : ''; ?>">
          <i class="far fa-circle nav-icon"></i>
          <p>Register</p>
        </a>
      </li>
    </ul>
  </li>
<?php
}
?>