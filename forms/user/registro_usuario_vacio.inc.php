<div class="card-body user-form">
  <div id="errors" class="mb-3"></div>

  <!-- Username -->
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" id="username" name="username" placeholder="e.g. jsmith" autofocus>
  </div>

  <!-- First name + Last name -->
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">First Name</label>
      <input type="text" class="form-control" id="nombres" name="nombres" placeholder="First name">
    </div>
    <div class="form-group col-md-6">
      <label for="apellidos">Last Name</label>
      <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Last name">
    </div>
  </div>

  <!-- Email -->
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com">
  </div>

  <!-- Password + Confirm -->
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Min. 6 characters">
    </div>
    <div class="form-group col-md-6">
      <label for="password-confirmation">Confirm Password</label>
      <input type="password" class="form-control" id="password-confirmation" name="password-confirmation" placeholder="Repeat password">
    </div>
  </div>

  <!-- Roles -->
  <div class="form-group mb-0">
    <label>Roles</label>
    <div class="role-grid">
      <label class="role-card">
        <input type="checkbox" name="cargo[]" value="1" id="admin">
        <span class="role-card-inner">
          <i class="fas fa-shield-alt"></i>
          <span>Admin</span>
        </span>
      </label>
      <label class="role-card">
        <input type="checkbox" name="cargo[]" value="3" id="rfq">
        <span class="role-card-inner">
          <i class="fas fa-file-alt"></i>
          <span>RFQ</span>
        </span>
      </label>
      <label class="role-card">
        <input type="checkbox" name="cargo[]" value="2" id="fulfillment">
        <span class="role-card-inner">
          <i class="fas fa-boxes"></i>
          <span>Fulfillment</span>
        </span>
      </label>
      <label class="role-card">
        <input type="checkbox" name="cargo[]" value="4" id="accounting">
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
  <button type="submit" class="btn btn-primary btn-sm" name="registrar_usuario">
    <i class="fas fa-check mr-1"></i> Save User
  </button>
</div>
