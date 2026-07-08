<?php
// Admin Settings sidebar link is hidden for now — the Notification Mailbox / email
// notifications feature is on hold and may change. The route perfil/admin/settings still
// works if visited directly. To restore the link, uncomment the block below.
//
// if ($_SESSION['user']->is_admin()) {
//   $currentRoute = $partes_ruta[2] ?? null;
//   echo '<li class="nav-item"><a href="' . ADMIN_SETTINGS . '" class="nav-link '
//      . ($currentRoute === 'admin' ? 'active' : '') . '">'
//      . '<i class="nav-icon fas fa-shield-alt"></i><p>Admin Settings</p></a></li>';
// }
