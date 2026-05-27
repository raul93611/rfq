<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$ms = RepositorioUsuario::get_ms_tokens($conexion, $_SESSION['user']->obtener_id());
Conexion::cerrar_conexion();

$ms_connected = !empty($ms['ms_refresh_token']);
$ms_email     = htmlspecialchars($ms['ms_email'] ?? '');
$user         = $_SESSION['user'];

$flash_connected    = isset($_GET['ms_connected']);
$flash_disconnected = isset($_GET['ms_disconnected']);
$flash_ms_error     = isset($_GET['ms_error']) ? htmlspecialchars($_GET['ms_error']) : '';
?>
<div class="content-wrapper ac-page">
  <div class="content-header">
    <div class="container-fluid">
      <div class="nf-page-head">
        <div class="nf-page-head-left">
          <a href="javascript:history.back()" class="nf-back-btn" aria-label="Back">
            <i class="fas fa-arrow-left"></i>
          </a>
          <div>
            <div class="nf-page-title">My Account</div>
            <div class="nf-page-sub">Manage your profile and connected services</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <?php if ($flash_connected): ?>
        <div class="alert alert-success alert-dismissible fade show nf-alert-success" role="alert">
          <i class="fas fa-check-circle mr-2"></i> Microsoft account connected successfully.
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      <?php endif; ?>
      <?php if ($flash_disconnected): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <i class="fas fa-unlink mr-2"></i> Microsoft account disconnected.
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      <?php endif; ?>
      <?php if ($flash_ms_error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle mr-2"></i> <?= $flash_ms_error ?>
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      <?php endif; ?>

      <div class="ac-grid">

        <!-- Personal Information card -->
        <div class="ac-card">
          <div class="ac-card-head">
            <div class="ac-card-head-icon">
              <i class="fas fa-user"></i>
            </div>
            <div class="ac-card-head-text">
              <div class="ac-card-head-title">Personal Information</div>
              <div class="ac-card-head-sub">Your name, email and password</div>
            </div>
          </div>
          <div class="ac-card-body">

            <!-- Profile section -->
            <div class="ac-section">
              <div class="ac-section-title">Profile</div>
              <div class="ac-row">
                <div class="ac-field">
                  <label class="ac-label" for="ac_nombres">First name</label>
                  <input class="ac-input" type="text" id="ac_nombres" name="nombres"
                    value="<?= htmlspecialchars($user->obtener_nombres()) ?>" autocomplete="given-name">
                </div>
                <div class="ac-field">
                  <label class="ac-label" for="ac_apellidos">Last name</label>
                  <input class="ac-input" type="text" id="ac_apellidos" name="apellidos"
                    value="<?= htmlspecialchars($user->obtener_apellidos()) ?>" autocomplete="family-name">
                </div>
              </div>
              <div style="height:12px"></div>
              <div class="ac-row ac-row-1">
                <div class="ac-field">
                  <label class="ac-label" for="ac_email">Email</label>
                  <input class="ac-input" type="email" id="ac_email" name="email"
                    value="<?= htmlspecialchars($user->obtener_email()) ?>" autocomplete="email">
                </div>
              </div>
              <div style="height:14px"></div>
              <div class="ac-actions">
                <button type="button" class="ap-btn" id="ac_profile_cancel">Cancel</button>
                <button type="button" class="ap-btn ap-btn-primary" id="ac_profile_save">Save profile</button>
              </div>
            </div>

            <!-- Change password section -->
            <div class="ac-section">
              <div class="ac-section-title">Change password</div>
              <div class="ac-row ac-row-1">
                <div class="ac-field">
                  <label class="ac-label" for="ac_curr_pass">Current password</label>
                  <input class="ac-input" type="password" id="ac_curr_pass" name="current_password" autocomplete="current-password">
                </div>
              </div>
              <div style="height:12px"></div>
              <div class="ac-row">
                <div class="ac-field">
                  <label class="ac-label" for="ac_new_pass">New password</label>
                  <input class="ac-input" type="password" id="ac_new_pass" name="new_password"
                    placeholder="At least 8 characters" autocomplete="new-password">
                </div>
                <div class="ac-field">
                  <label class="ac-label" for="ac_confirm_pass">Confirm new password</label>
                  <input class="ac-input" type="password" id="ac_confirm_pass" name="confirm_password"
                    placeholder="Re-enter new password" autocomplete="new-password">
                </div>
              </div>
              <div style="height:14px"></div>
              <div class="ac-actions">
                <button type="button" class="ap-btn ap-btn-primary" id="ac_pass_save">Update password</button>
              </div>
            </div>

          </div>
        </div>

        <!-- Microsoft Account card -->
        <div class="ac-card">
          <div class="ac-card-head">
            <div class="ac-card-head-icon ac-card-head-icon-ms">
              <div class="ac-ms-logo">
                <span class="ac-ms-logo-r1"></span><span class="ac-ms-logo-r2"></span>
                <span class="ac-ms-logo-r3"></span><span class="ac-ms-logo-r4"></span>
              </div>
            </div>
            <div class="ac-card-head-text">
              <div class="ac-card-head-title">Microsoft Account</div>
              <div class="ac-card-head-sub">Required to receive @mention notifications by email</div>
            </div>
          </div>
          <div class="ac-card-body">
            <?php if ($ms_connected): ?>
              <div class="ac-ms-connected">
                <div class="ac-ms-conn-left">
                  <div class="ac-ms-illus">
                    <div class="ac-ms-logo ac-ms-logo-lg">
                      <span class="ac-ms-logo-r1"></span><span class="ac-ms-logo-r2"></span>
                      <span class="ac-ms-logo-r3"></span><span class="ac-ms-logo-r4"></span>
                    </div>
                  </div>
                  <div class="ac-ms-conn-email-block">
                    <div class="ac-ms-conn-email-label">Connected as</div>
                    <div class="ac-ms-conn-email"><?= $ms_email ?></div>
                  </div>
                </div>
                <div class="ac-ms-conn-right">
                  <span class="ac-badge-connected">
                    <span class="ac-badge-connected-dot"></span>
                    Connected
                  </span>
                  <a href="<?= MS_DISCONNECT ?>" class="ap-btn-danger-link"
                     onclick="return confirm('Disconnect your Microsoft account?')">Disconnect</a>
                </div>
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
                  <div class="ac-ms-text-title">Not connected</div>
                  <div class="ac-ms-text-desc">
                    Connect your Microsoft account so notifications can also be delivered
                    to your Outlook inbox. You'll still see in-app alerts either way.
                  </div>
                </div>
                <a href="<?= MS_CONNECT ?>" class="ac-ms-btn" id="ac_ms_connect_btn">
                  <div class="ac-ms-logo">
                    <span class="ac-ms-logo-r1"></span><span class="ac-ms-logo-r2"></span>
                    <span class="ac-ms-logo-r3"></span><span class="ac-ms-logo-r4"></span>
                  </div>
                  Connect Microsoft Account
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div><!-- /ac-grid -->
    </div>
  </div>
</div>

<script>
(function () {
  // Profile save
  const profileOriginal = {
    nombres:   document.getElementById('ac_nombres').value,
    apellidos: document.getElementById('ac_apellidos').value,
    email:     document.getElementById('ac_email').value,
  };

  document.getElementById('ac_profile_cancel').addEventListener('click', function () {
    document.getElementById('ac_nombres').value   = profileOriginal.nombres;
    document.getElementById('ac_apellidos').value = profileOriginal.apellidos;
    document.getElementById('ac_email').value     = profileOriginal.email;
  });

  document.getElementById('ac_profile_save').addEventListener('click', function () {
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Saving…';

    fetch('<?= ACCOUNT_UPDATE_PROFILE ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        nombres:   document.getElementById('ac_nombres').value,
        apellidos: document.getElementById('ac_apellidos').value,
        email:     document.getElementById('ac_email').value,
      }),
    })
    .then(r => r.json())
    .then(data => {
      if (data.ok) {
        toastr.success('Profile saved.');
      } else {
        toastr.error(data.error || 'Failed to save profile.');
      }
    })
    .catch(() => toastr.error('Network error.'))
    .finally(() => {
      btn.disabled = false;
      btn.textContent = 'Save profile';
    });
  });

  // Password save
  document.getElementById('ac_pass_save').addEventListener('click', function () {
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Updating…';

    fetch('<?= ACCOUNT_UPDATE_PASSWORD ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        current_password:  document.getElementById('ac_curr_pass').value,
        new_password:      document.getElementById('ac_new_pass').value,
        confirm_password:  document.getElementById('ac_confirm_pass').value,
      }),
    })
    .then(r => r.json())
    .then(data => {
      if (data.ok) {
        toastr.success('Password updated.');
        document.getElementById('ac_curr_pass').value   = '';
        document.getElementById('ac_new_pass').value    = '';
        document.getElementById('ac_confirm_pass').value = '';
      } else {
        toastr.error(data.error || 'Failed to update password.');
      }
    })
    .catch(() => toastr.error('Network error.'))
    .finally(() => {
      btn.disabled = false;
      btn.textContent = 'Update password';
    });
  });
})();
</script>
