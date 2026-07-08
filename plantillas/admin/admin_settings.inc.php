<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
if (!$_SESSION['user']->is_admin()) {
  include_once 'plantillas/utilities/muro.inc.php';
  return;
}

Conexion::abrir_conexion();
$mailbox = NotificationMailboxRepository::get(Conexion::obtener_conexion());
Conexion::cerrar_conexion();

$mailbox_connected = !empty($mailbox['ms_refresh_token']);
$mailbox_email     = htmlspecialchars($mailbox['ms_email'] ?? '');
$flash_connected    = isset($_GET['mailbox_connected']);
$flash_disconnected = isset($_GET['mailbox_disconnected']);
?>
<div class="content-wrapper ac-page">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Admin Settings</h1>
      <p class="page-subtitle">Organization · Integrations &amp; connected services</p>
    </div>
  </div>

  <div class="content" style="padding-top: 24px; padding-bottom: 80px;">
    <div class="container-fluid">
      <?php if ($flash_connected): ?>
        <div class="alert alert-success alert-dismissible fade show nf-alert-success" role="alert">
          <i class="fas fa-check-circle mr-2"></i> Notification mailbox connected.
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      <?php endif; ?>
      <?php if ($flash_disconnected): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <i class="fas fa-unlink mr-2"></i> Notification mailbox disconnected.
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      <?php endif; ?>

      <div class="ac-grid" style="max-width:720px;">

        <!-- Notification Mailbox card -->
        <div class="ac-card">
          <div class="ac-card-head">
            <div class="ac-card-head-icon ac-card-head-icon-ms">
              <div class="ac-ms-logo">
                <span class="ac-ms-logo-r1"></span><span class="ac-ms-logo-r2"></span>
                <span class="ac-ms-logo-r3"></span><span class="ac-ms-logo-r4"></span>
              </div>
            </div>
            <div class="ac-card-head-text">
              <div class="ac-card-head-title">
                Notification Mailbox
                <span class="ac-admin-tag"><i class="fas fa-shield-alt"></i> Admin only</span>
              </div>
              <div class="ac-card-head-sub">
                The shared Microsoft mailbox that sends @mention and quote-watcher notification emails for the whole team.
              </div>
            </div>
          </div>
          <div class="ac-card-body">
            <?php if ($mailbox_connected): ?>
              <div class="ac-ms-connected">
                <div class="ac-ms-conn-left">
                  <div class="ac-ms-illus">
                    <div class="ac-ms-logo ac-ms-logo-lg">
                      <span class="ac-ms-logo-r1"></span><span class="ac-ms-logo-r2"></span>
                      <span class="ac-ms-logo-r3"></span><span class="ac-ms-logo-r4"></span>
                    </div>
                  </div>
                  <div class="ac-ms-conn-email-block">
                    <div class="ac-ms-conn-email-label">Connected mailbox</div>
                    <div class="ac-ms-conn-email"><?= $mailbox_email ?></div>
                  </div>
                </div>
                <div class="ac-ms-conn-right">
                  <span class="ac-badge-connected">
                    <span class="ac-badge-connected-dot"></span>
                    Connected
                  </span>
                  <a href="<?= MAILBOX_DISCONNECT ?>" class="ap-btn-danger-link"
                     onclick="return confirm('Disconnect the notification mailbox? Email notifications will stop until it is reconnected.')">Disconnect</a>
                </div>
              </div>
              <div class="ac-status-line">
                <span class="ac-status-dot ac-status-dot-connected"></span>
                Status: <strong>Connected</strong> · sending from <strong><?= $mailbox_email ?></strong>
              </div>
            <?php else: ?>
              <div class="ac-ms-disconnected">
                <div class="ac-ms-illus">
                  <div class="ac-ms-logo ac-ms-logo-lg">
                    <span class="ac-ms-logo-r1"></span><span class="ac-ms-logo-r2"></span>
                    <span class="ac-ms-logo-r3"></span><span class="ac-ms-logo-r4"></span>
                  </div>
                </div>
                <div class="ac-ms-text">
                  <div class="ac-ms-text-title">No mailbox connected</div>
                  <div class="ac-ms-text-desc">
                    Connect a shared Microsoft mailbox so the app can send @mention and quote-watcher
                    notification emails on behalf of the team. Sign in with the shared mailbox account
                    (e.g. portal@e-logic.us), not your own. In-app alerts work either way.
                  </div>
                </div>
                <button type="button" class="ac-ms-btn" id="mailbox_connect_btn">
                  <div class="ac-ms-logo">
                    <span class="ac-ms-logo-r1"></span><span class="ac-ms-logo-r2"></span>
                    <span class="ac-ms-logo-r3"></span><span class="ac-ms-logo-r4"></span>
                  </div>
                  Connect mailbox
                </button>
              </div>
              <div class="ac-status-line">
                <span class="ac-status-dot ac-status-dot-disconnected"></span>
                Status: <strong>Not connected</strong> · notification emails are not being sent
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div><!-- /ac-grid -->
    </div>
  </div>

  <div class="quote-action-bar">
    <div class="quote-action-bar__left">
      <a href="<?= PERFIL ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
      </a>
    </div>
  </div>
</div>

<script>
(function () {
  var btn = document.getElementById('mailbox_connect_btn');
  if (!btn) return;
  btn.addEventListener('click', function () {
    var popup = window.open('<?= MAILBOX_CONNECT ?>', '_blank');
    if (!popup) return; // popup blocked
    var timer = setInterval(function () {
      if (popup.closed) {
        clearInterval(timer);
        window.location.href = '<?= ADMIN_SETTINGS ?>?mailbox_connected=1';
      }
    }, 500);
  });
})();
</script>
