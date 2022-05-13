<div class="card-body">
  <div class="form-group">
    <label for="nombre_usuario">Username:</label>
    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Username" autofocus required>
  </div>
  <div class="form-group">
    <label for="password1">Password:</label>
    <input type="password" class="form-control" id="password1" name="password1" placeholder="Password" required>
  </div>
  <div class="form-group">
    <label for="password2">Confirm password:</label>
    <input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm password" required>
  </div>
  <div class="form-group">
    <label for="nombres">First names:</label>
    <input type="text" class="form-control" id="nombres" name="nombres" placeholder="First names" required>
  </div>
  <div class="form-group">
    <label for="apellidos">Last names:</label>
    <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Last names" required>
  </div>
  <div class="form-group">
    <label for="email">E-mail:</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
  </div>
  <div class="form-group">
    <label for="cargo">Roles:</label>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="1" class="custom-control-input" id="admin">
      <label class="custom-control-label" for="admin">Admin</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="3" class="custom-control-input" id="rfq">
      <label class="custom-control-label" for="rfq">RFQ</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="2" class="custom-control-input" id="fulfillment">
      <label class="custom-control-label" for="fulfillment">Fulfillment</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="4" class="custom-control-input" id="accounting">
      <label class="custom-control-label" for="accounting">Accounting</label>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="registrar_usuario"><i class="fa fa-check"></i> Sign in</button>
</div>
