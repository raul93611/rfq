<div class="card-body">
  <div class="form-group">
    <label for="nombre_usuario">Username:</label>
    <input
      type="text"
      class="form-control"
      id="nombre_usuario"
      name="nombre_usuario"
      placeholder="Enter your username"
      autofocus
      required
      <?php $validador->mostrar_nombre_usuario(); ?>>
    <small class="form-text text-muted">Choose a unique username for your account.</small>
    <?php $validador->mostrar_error_nombre_usuario(); ?>
  </div>

  <div class="form-group">
    <label for="password1">Password:</label>
    <input
      type="password"
      class="form-control"
      id="password1"
      name="password1"
      placeholder="Enter your password"
      required>
    <small class="form-text text-muted">Create a strong password with at least 8 characters.</small>
    <?php $validador->mostrar_error_password1(); ?>
  </div>

  <div class="form-group">
    <label for="password2">Confirm Password:</label>
    <input
      type="password"
      class="form-control"
      id="password2"
      name="password2"
      placeholder="Re-enter your password"
      required>
    <small class="form-text text-muted">Re-enter your password to confirm it matches.</small>
    <?php $validador->mostrar_error_password2(); ?>
  </div>

  <div class="form-group">
    <label for="nombres">First Names:</label>
    <input
      type="text"
      class="form-control"
      id="nombres"
      name="nombres"
      placeholder="Enter your first names"
      required
      <?php $validador->mostrar_nombres(); ?>>
    <small class="form-text text-muted">Provide your given names as they appear on your ID.</small>
    <?php $validador->mostrar_error_nombres(); ?>
  </div>

  <div class="form-group">
    <label for="apellidos">Last Names:</label>
    <input
      type="text"
      class="form-control"
      id="apellidos"
      name="apellidos"
      placeholder="Enter your last names"
      required
      <?php $validador->mostrar_apellidos(); ?>>
    <small class="form-text text-muted">Provide your family names or surnames.</small>
    <?php $validador->mostrar_error_apellidos(); ?>
  </div>

  <div class="form-group">
    <label for="email">E-mail:</label>
    <input
      type="email"
      class="form-control"
      id="email"
      name="email"
      placeholder="Enter your email address"
      required>
    <small class="form-text text-muted">Enter a valid email address for account notifications.</small>
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
  <button
    type="submit"
    class="btn btn-primary"
    name="registrar_usuario">
    <i class="fa fa-check"></i> Sign in
  </button>
</div>