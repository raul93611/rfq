<?php if ($_SESSION['user']->is_admin()): ?>
  <?php
  $currentRoute = $partes_ruta[2] ?? null;
  $currentSubRoute = $partes_ruta[3] ?? null;
  ?>
  <li class="nav-item <?= $currentRoute === 'user' ? 'menu-open' : ''; ?>">
    <a href="#" class="nav-link <?= $currentRoute === 'user' ? 'active' : ''; ?>">
      <i class="nav-icon fas fa-users"></i>
      <p>
        Users
        <i class="right fa fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="<?= USERS; ?>" class="nav-link <?= $currentSubRoute === 'users' ? 'active' : ''; ?>">
          <i class="far fa-circle nav-icon"></i>
          <p>All Users</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= REGISTRO; ?>" class="nav-link <?= $currentSubRoute === 'registro' ? 'active' : ''; ?>">
          <i class="far fa-circle nav-icon"></i>
          <p>Register</p>
        </a>
      </li>
    </ul>
  </li>
<?php endif; ?>