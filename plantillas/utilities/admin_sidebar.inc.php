<?php if ($_SESSION['user']->is_admin()): ?>
  <?php $currentRoute = $partes_ruta[2] ?? null; ?>
  <li class="nav-item">
    <a href="<?= ADMIN_SETTINGS; ?>" class="nav-link <?= $currentRoute === 'admin' ? 'active' : ''; ?>">
      <i class="nav-icon fas fa-shield-alt"></i>
      <p>Admin Settings</p>
    </a>
  </li>
<?php endif; ?>
