<?php
Conexion::abrir_conexion();
$user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $id_user);
Conexion::cerrar_conexion();
if(!$_SESSION['user']-> is_admin()){
  Redireccion::redirigir1(PERFIL);
}
?>
<input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
<div class="card-body">
  <div class="form-group">
    <label for="username">Username:</label>
    <input type="text" class="form-control" id="username" name="username" placeholder="Username ..." autofocus required value="<?php echo $user-> obtener_nombre_usuario(); ?>">
  </div>
  <div class="form-group">
    <label for="nombres">First names:</label>
    <input type="text" class="form-control" id="nombres" name="nombres" placeholder="First names ..." required value="<?php echo $user-> obtener_nombres(); ?>">
  </div>
  <div class="form-group">
    <label for="apellidos">Last names:</label>
    <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Last names ..." required value="<?php echo $user-> obtener_apellidos(); ?>">
  </div>
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Email ..." required value="<?php echo $user-> obtener_email(); ?>">
  </div>
  <div class="form-group">
    <label for="password1">Password:</label>
    <input type="password" class="form-control" id="password1" name="password1" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="cargo">Roles:</label>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="1" class="custom-control-input" id="admin" <?php echo $user-> is_admin() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="admin">Admin</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="3" class="custom-control-input" id="rfq" <?php echo $user-> is_rfq() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="rfq">RFQ</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="2" class="custom-control-input" id="fulfillment" <?php echo $user-> is_fulfillment() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="fulfillment">Fulfillment</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" name="cargo[]" value="4" class="custom-control-input" id="accounting" <?php echo $user-> is_accounting() ? 'checked' : ''; ?>>
      <label class="custom-control-label" for="accounting">Accounting</label>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="edit_user"><i class="fa fa-check"></i> Sign in</button>
</div>
