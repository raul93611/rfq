<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$notif_prefs = RepositorioUsuario::get_notif_prefs($conexion, $_SESSION['user']->obtener_id());
Conexion::cerrar_conexion();

$user = $_SESSION['user'];
?>
<div class="content-wrapper ac-page">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">My Account</h1>
      <p class="page-subtitle">Manage your profile and notification preferences</p>
    </div>
  </div>

  <div class="content" style="padding-top: 24px; padding-bottom: 80px;">
    <div class="container-fluid">

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

        <!-- Notification Preferences card -->
        <div class="ac-card">
          <div class="ac-card-head">
            <div class="ac-card-head-icon">
              <i class="fas fa-bell"></i>
            </div>
            <div class="ac-card-head-text">
              <div class="ac-card-head-title">Notification Preferences</div>
              <div class="ac-card-head-sub">Choose how you want to be notified</div>
            </div>
          </div>
          <div class="ac-card-body">
            <div class="ac-section">
              <div class="ac-notif-row">
                <div class="ac-notif-info">
                  <div class="ac-notif-label">In-app notifications</div>
                  <div class="ac-notif-desc">Bell icon alerts when you're mentioned or someone comments on your quotes</div>
                </div>
                <label class="ac-toggle">
                  <input type="checkbox" id="ac_notif_inapp" <?= $notif_prefs['notif_inapp'] ? 'checked' : '' ?>>
                  <span class="ac-toggle-slider"></span>
                </label>
              </div>
              <div class="ac-notif-row">
                <div class="ac-notif-info">
                  <div class="ac-notif-label">Email notifications</div>
                  <div class="ac-notif-desc">Receive email alerts when you're mentioned or someone comments on your quotes</div>
                </div>
                <label class="ac-toggle">
                  <input type="checkbox" id="ac_notif_email" <?= $notif_prefs['notif_email'] ? 'checked' : '' ?>>
                  <span class="ac-toggle-slider"></span>
                </label>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /ac-grid -->
    </div>
  </div>

  <div class="quote-action-bar">
    <div class="quote-action-bar__left">
      <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
      </a>
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

  // Notification preference toggles — save on change
  function saveNotifPrefs() {
    fetch('<?= ACCOUNT_UPDATE_NOTIFICATIONS ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        notif_inapp: document.getElementById('ac_notif_inapp').checked ? '1' : '0',
        notif_email: document.getElementById('ac_notif_email').checked ? '1' : '0',
      }),
    })
    .then(r => r.json())
    .then(data => { if (data.ok) toastr.success('Notification preferences saved.'); })
    .catch(() => toastr.error('Failed to save preferences.'));
  }
  document.getElementById('ac_notif_inapp').addEventListener('change', saveNotifPrefs);
  document.getElementById('ac_notif_email').addEventListener('change', saveNotifPrefs);

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
