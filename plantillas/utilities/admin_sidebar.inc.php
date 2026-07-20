<?php
// Restored for the Daily RFQ Digest Email feature — admins need a way to reach the
// Notification Mailbox connect flow, which the digest cron depends on to actually deliver.
if ($_SESSION['user']->is_admin()) {
  $currentRoute = $partes_ruta[2] ?? null;
  echo '<li class="nav-item"><a href="' . ADMIN_SETTINGS . '" class="nav-link '
     . ($currentRoute === 'admin' ? 'active' : '') . '">'
     . '<i class="nav-icon fas fa-shield-alt"></i><p>Admin Settings</p></a></li>';
}
