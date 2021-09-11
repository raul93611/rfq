<?php
Conexion::abrir_conexion();
$user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $id_user);
Conexion::cerrar_conexion();
if($_SESSION['cargo'] != 1){
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
    <label for="cargo">Level:</label>
    <select class="form-control" name="cargo" id="cargo">
      <option value="2" <?php if($user-> obtener_cargo() == 2){echo 'selected';} ?>>Fulfillment</option>
      <option value="3" <?php if($user-> obtener_cargo() == 3){echo 'selected';} ?>>RFQ</option>
      <option value="4" <?php if($user-> obtener_cargo() == 4){echo 'selected';} ?>>Accounting</option>
    </select>
  </div>
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Email ..." required value="<?php echo $user-> obtener_email(); ?>">
  </div>
  <div class="form-group">
    <label for="password1">Password:</label>
    <input type="password" class="form-control" id="password1" name="password1" placeholder="Password">
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="edit_user"><i class="fa fa-check"></i> Sign in</button>
</div>
