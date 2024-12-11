<input type="hidden" name="id_user" value="<?= $id_user; ?>">
<div class="card-body">
  <div id="errors"></div>

  <div class="form-group">
    <label for="username">Username:</label>
    <input
      type="text"
      class="form-control form-control-sm"
      id="username"
      name="username"
      placeholder="Username ..."
      autofocus
      value="<?= $user->obtener_nombre_usuario(); ?>">
    <small class="form-text text-muted">Enter the user's unique username.</small>
  </div>

  <div class="form-group">
    <label for="nombres">First Names:</label>
    <input
      type="text"
      class="form-control form-control-sm"
      id="nombres"
      name="nombres"
      placeholder="First names ..."
      value="<?= $user->obtener_nombres(); ?>">
    <small class="form-text text-muted">Provide the user's first names.</small>
  </div>

  <div class="form-group">
    <label for="apellidos">Last Names:</label>
    <input
      type="text"
      class="form-control form-control-sm"
      id="apellidos"
      name="apellidos"
      placeholder="Last names ..."
      value="<?= $user->obtener_apellidos(); ?>">
    <small class="form-text text-muted">Provide the user's last names.</small>
  </div>

  <div class="form-group">
    <label for="email">Email:</label>
    <input
      type="email"
      class="form-control form-control-sm"
      id="email"
      name="email"
      placeholder="Email ..."
      value="<?= $user->obtener_email(); ?>">
    <small class="form-text text-muted">Enter a valid email address.</small>
  </div>

  <div class="form-group">
    <label for="cargo">Roles:</label>
    <div class="custom-control custom-checkbox">
      <input
        type="checkbox"
        name="cargo[]"
        value="1"
        class="custom-control-input"
        id="admin"
        <?= $user->is_admin() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="admin">Admin</label>
      <small class="form-text text-muted">Grants full administrative privileges.</small>
    </div>
    <div class="custom-control custom-checkbox">
      <input
        type="checkbox"
        name="cargo[]"
        value="3"
        class="custom-control-input"
        id="rfq"
        <?= $user->is_rfq() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="rfq">RFQ</label>
      <small class="form-text text-muted">Allows managing Request for Quotations (RFQ).</small>
    </div>
    <div class="custom-control custom-checkbox">
      <input
        type="checkbox"
        name="cargo[]"
        value="2"
        class="custom-control-input"
        id="fulfillment"
        <?= $user->is_fulfillment() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="fulfillment">Fulfillment</label>
      <small class="form-text text-muted">Handles fulfillment-related tasks.</small>
    </div>
    <div class="custom-control custom-checkbox">
      <input
        type="checkbox"
        name="cargo[]"
        value="4"
        class="custom-control-input"
        id="accounting"
        <?= $user->is_accounting() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="accounting">Accounting</label>
      <small class="form-text text-muted">Manages accounting-related activities.</small>
    </div>
  </div>
</div>

<div class="card-footer">
  <a
    class="btn btn-primary"
    id="go_back"
    href="<?= USERS; ?>"
    title="Go back to the user list">
    <i class="fa fa-reply"></i>
  </a>
  <button
    type="submit"
    class="btn btn-success"
    name="edit_user"
    title="Save the user details">
    <i class="fa fa-check"></i> Save
  </button>
</div>