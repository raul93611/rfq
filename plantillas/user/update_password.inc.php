<?php
try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();
  $user = RepositorioUsuario::obtener_usuario_por_id($conexion, $id_user);
  if (!$user) {
    echo '<div class="alert alert-danger">User not found.</div>';
    exit;
  }
} catch (Exception $e) {
  echo '<div class="alert alert-danger">An error occurred while fetching user data. Please try again later.</div>';
  exit;
} finally {
  Conexion::cerrar_conexion();
}
?>
<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Update Password</h1>
      <p class="page-subtitle"><?= htmlspecialchars($user->obtener_nombre_usuario(), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <a href="<?= USERS ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Back to Users
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
          <div class="card chart-card">
            <form id="update-password-form" role="form" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
              <?php include_once 'forms/user/update_password.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<script src="<?= asset_url('js/users.js'); ?>"></script>
