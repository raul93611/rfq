<input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
<div class="card-body">
  <div id="errors"></div>
  <div class="form-group">
    <label for="username">Username:</label>
    <input type="text" class="form-control form-control-sm" id="username" name="username" placeholder="Username ..." autofocus value="<?php echo $user->obtener_nombre_usuario(); ?>">
  </div>
  <div class="form-group">
    <label for="nombres">First names:</label>
    <input type="text" class="form-control form-control-sm" id="nombres" name="nombres" placeholder="First names ..." value="<?php echo $user->obtener_nombres(); ?>">
  </div>
  <div class="form-group">
    <label for="apellidos">Last names:</label>
    <input type="text" class="form-control form-control-sm" id="apellidos" name="apellidos" placeholder="Last names ..." value="<?php echo $user->obtener_apellidos(); ?>">
  </div>
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Email ..." value="<?php echo $user->obtener_email(); ?>">
  </div>
  <div class="form-group">
    <label for="cargo">Roles:</label>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="1" class="custom-control-input" id="admin" <?php echo $user->is_admin() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="admin">Admin</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="3" class="custom-control-input" id="rfq" <?php echo $user->is_rfq() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="rfq">RFQ</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="2" class="custom-control-input" id="fulfillment" <?php echo $user->is_fulfillment() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="fulfillment">Fulfillment</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="4" class="custom-control-input" id="accounting" <?php echo $user->is_accounting() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="accounting">Accounting</label>
    </div>
  </div>
</div>
<div class="card-footer">
  <a class="btn btn-primary" id="go_back" href="<?php echo USERS; ?>"><i class="fa fa-reply"></i></a>
  <button type="submit" class="btn btn-success" name="edit_user"><i class="fa fa-check"></i> Save</button>
</div>