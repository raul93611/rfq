<?php
Conexion::abrir_conexion();
$user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $id_user);
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><?= $user-> obtener_nombre_usuario() ?></h1>
        </div>
        <div class="col-sm-6">

        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-key"></i> Update password</h3>
            </div>
            <form id="update-password-form" role="form" method="post" action="">
              <?php
                include_once 'forms/user/update_password.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script src="<?php echo RUTA_JS; ?>users.js"></script>
