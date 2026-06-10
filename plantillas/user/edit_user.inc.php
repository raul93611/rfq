<?php
try {
  Conexion::abrir_conexion();
  $user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $id_user);
} finally {
  Conexion::cerrar_conexion();
}
?>
<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Edit User</h1>
      <p class="page-subtitle"><?= htmlspecialchars($user->obtener_nombre_usuario(), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <a href="<?= USERS ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Back to Users
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
          <div class="card chart-card">
            <form id="edit-user-form" role="form" method="post" action="">
              <?php include_once 'forms/user/empty_edit_user_form.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<script src="<?= asset_url('js/users.js'); ?>"></script>
