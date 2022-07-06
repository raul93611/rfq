<?php
if ($_SESSION['user']->is_admin()) {
?>
  <li class="nav-item <?php echo $gestor_actual == 'edit_user' || $gestor_actual == 'users' || $gestor_actual == 'registro' || $gestor_actual == 'registro_correcto' ? 'menu-open' : ''; ?>">
    <a href="#" class="nav-link <?php echo $gestor_actual == 'edit_user' || $gestor_actual == 'users' || $gestor_actual == 'registro' || $gestor_actual == 'registro_correcto' ? 'active' : ''; ?>">
      <i class="nav-icon fas fa-users"></i>
      <p>
        Users
        <i class="right fa fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="<?php echo USERS; ?>" class="nav-link <?php echo $gestor_actual == 'users' ? 'active' : ''; ?>">
          <i class="far fa-circle nav-icon"></i>
          <p>All Users</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo REGISTRO; ?>" class="nav-link <?php echo $gestor_actual == 'registro' || $gestor_actual == 'registro_correcto' ? 'active' : ''; ?>">
          <i class="far fa-circle nav-icon"></i>
          <p>Register</p>
        </a>
      </li>
    </ul>
  </li>
<?php
}
?>