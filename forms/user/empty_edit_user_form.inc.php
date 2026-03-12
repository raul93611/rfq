<input type="hidden" name="id_user" value="<?= $id_user; ?>">

<div class="card-body user-form">
  <div id="errors" class="mb-3"></div>

  <!-- Username -->
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" id="username" name="username" autofocus
      value="<?= htmlspecialchars($user->obtener_nombre_usuario(), ENT_QUOTES, 'UTF-8'); ?>">
  </div>

  <!-- First name + Last name -->
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">First Name</label>
      <input type="text" class="form-control" id="nombres" name="nombres"
        value="<?= htmlspecialchars($user->obtener_nombres(), ENT_QUOTES, 'UTF-8'); ?>">
    </div>
    <div class="form-group col-md-6">
      <label for="apellidos">Last Name</label>
      <input type="text" class="form-control" id="apellidos" name="apellidos"
        value="<?= htmlspecialchars($user->obtener_apellidos(), ENT_QUOTES, 'UTF-8'); ?>">
    </div>
  </div>

  <!-- Email -->
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" class="form-control" id="email" name="email"
      value="<?= htmlspecialchars($user->obtener_email(), ENT_QUOTES, 'UTF-8'); ?>">
  </div>

  <!-- Roles -->
  <div class="form-group mb-0">
    <label>Roles</label>
    <div class="role-grid">
      <label class="role-card">
        <input type="checkbox" name="cargo[]" value="1" id="admin" <?= $user->is_admin() ? 'checked' : ''; ?>>
        <span class="role-card-inner">
          <i class="fas fa-shield-alt"></i>
          <span>Admin</span>
        </span>
      </label>
      <label class="role-card">
        <input type="checkbox" name="cargo[]" value="3" id="rfq" <?= $user->is_rfq() ? 'checked' : ''; ?>>
        <span class="role-card-inner">
          <i class="fas fa-file-alt"></i>
          <span>RFQ</span>
        </span>
      </label>
      <label class="role-card">
        <input type="checkbox" name="cargo[]" value="2" id="fulfillment" <?= $user->is_fulfillment() ? 'checked' : ''; ?>>
        <span class="role-card-inner">
          <i class="fas fa-boxes"></i>
          <span>Fulfillment</span>
        </span>
      </label>
      <label class="role-card">
        <input type="checkbox" name="cargo[]" value="4" id="accounting" <?= $user->is_accounting() ? 'checked' : ''; ?>>
        <span class="role-card-inner">
          <i class="fas fa-calculator"></i>
          <span>Accounting</span>
        </span>
      </label>
    </div>
  </div>
</div>

<div class="card-footer d-flex justify-content-end" style="background:transparent;border-top:1px solid #f0f2f5;gap:8px;">
  <a class="btn btn-secondary btn-sm" href="<?= USERS; ?>">
    <i class="fas fa-times mr-1"></i> Cancel
  </a>
  <button type="submit" class="btn btn-primary btn-sm" name="edit_user">
    <i class="fas fa-check mr-1"></i> Save User
  </button>
</div>
