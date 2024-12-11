<div class="card-body">
  <div id="errors"></div>

  <div class="form-group">
    <label for="username">Username:</label>
    <input
      type="text"
      class="form-control form-control-sm"
      id="username"
      name="username"
      placeholder="Enter username"
      autofocus>
    <small class="form-text text-muted">Choose a unique username for the user.</small>
  </div>

  <div class="form-group">
    <label for="nombres">First Names:</label>
    <input
      type="text"
      class="form-control form-control-sm"
      id="nombres"
      name="nombres"
      placeholder="Enter first names">
    <small class="form-text text-muted">Provide the user's given names.</small>
  </div>

  <div class="form-group">
    <label for="apellidos">Last Names:</label>
    <input
      type="text"
      class="form-control form-control-sm"
      id="apellidos"
      name="apellidos"
      placeholder="Enter last names">
    <small class="form-text text-muted">Provide the user's family names or surnames.</small>
  </div>

  <div class="form-group">
    <label for="email">E-mail:</label>
    <input
      type="email"
      class="form-control form-control-sm"
      id="email"
      name="email"
      placeholder="Enter email address">
    <small class="form-text text-muted">Use a valid email address for correspondence.</small>
  </div>

  <div class="form-group">
    <label for="password">Password:</label>
    <input
      type="password"
      class="form-control form-control-sm"
      id="password"
      name="password"
      placeholder="Enter password">
    <small class="form-text text-muted">Create a strong password for the account.</small>
  </div>

  <div class="form-group">
    <label for="password-confirmation">Confirm Password:</label>
    <input
      type="password"
      class="form-control form-control-sm"
      id="password-confirmation"
      name="password-confirmation"
      placeholder="Confirm the password">
    <small class="form-text text-muted">Re-enter the password to confirm.</small>
  </div>

  <div class="form-group">
    <label for="cargo">Roles:</label>
    <div class="custom-control custom-checkbox">
      <input
        type="checkbox"
        name="cargo[]"
        value="1"
        class="custom-control-input"
        id="admin">
      <label class="custom-control-label" for="admin">Admin</label>
      <small class="form-text text-muted">Grants full administrative privileges.</small>
    </div>
    <div class="custom-control custom-checkbox">
      <input
        type="checkbox"
        name="cargo[]"
        value="3"
        class="custom-control-input"
        id="rfq">
      <label class="custom-control-label" for="rfq">RFQ</label>
      <small class="form-text text-muted">Allows managing Request for Quotations (RFQ).</small>
    </div>
    <div class="custom-control custom-checkbox">
      <input
        type="checkbox"
        name="cargo[]"
        value="2"
        class="custom-control-input"
        id="fulfillment">
      <label class="custom-control-label" for="fulfillment">Fulfillment</label>
      <small class="form-text text-muted">Handles fulfillment-related tasks.</small>
    </div>
    <div class="custom-control custom-checkbox">
      <input
        type="checkbox"
        name="cargo[]"
        value="4"
        class="custom-control-input"
        id="accounting">
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
    name="registrar_usuario"
    title="Save the user details">
    <i class="fa fa-check"></i> Save
  </button>
</div>