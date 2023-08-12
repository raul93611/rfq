<div class="card-body">
  <div id="errors"></div>
  <div class="form-group">
    <label for="username">Username:</label>
    <input type="text" class="form-control form-control-sm" id="username" name="username" placeholder="Username" autofocus>
  </div>
  <div class="form-group">
    <label for="nombres">First names:</label>
    <input type="text" class="form-control form-control-sm" id="nombres" name="nombres" placeholder="First names">
  </div>
  <div class="form-group">
    <label for="apellidos">Last names:</label>
    <input type="text" class="form-control form-control-sm" id="apellidos" name="apellidos" placeholder="Last names">
  </div>
  <div class="form-group">
    <label for="email">E-mail:</label>
    <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Email">
  </div>
  <div class="form-group">
    <label for="password">Password:</label>
    <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="password-confirmation">Confirm password:</label>
    <input type="password" class="form-control form-control-sm" id="password-confirmation" name="password-confirmation" placeholder="Confirm password">
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
  <a class="btn btn-primary" id="go_back" href="<?php echo USERS; ?>"><i class="fa fa-reply"></i></a>
  <button type="submit" class="btn btn-success" name="registrar_usuario"><i class="fa fa-check"></i> Save</button>
</div>