<?php
// Open connection, fetch user, and ensure connection is closed
try {
  Conexion::abrir_conexion();
  $user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $id_user);
} finally {
  Conexion::cerrar_conexion();
}
?>
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Users</h1>
        </div>
        <!-- Optional Space for Additional Controls -->
        <div class="col-sm-6"></div>
      </div>
    </div>
  </section>

  <!-- Main Content Section -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <!-- User Edit Card -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fa fa-user-plus"></i> Edit User
              </h3>
            </div>
            <form id="edit-user-form" role="form" method="post" action="">
              <?php include_once 'forms/user/empty_edit_user_form.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- JavaScript -->
<script src="<?= RUTA_JS; ?>users.js"></script>