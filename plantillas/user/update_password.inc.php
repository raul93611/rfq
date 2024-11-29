<div class="content-wrapper">
  <?php
  // Open the connection and fetch user data
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Fetch the user data
    $user = RepositorioUsuario::obtener_usuario_por_id($conexion, $id_user);

    // Check if the user exists
    if (!$user) {
      // If user is not found, show a message and exit
      echo '<div class="alert alert-danger">User not found.</div>';
      exit;
    }
  } catch (Exception $e) {
    // Show a user-friendly error message
    echo '<div class="alert alert-danger">An error occurred while fetching user data. Please try again later.</div>';
    exit; // Exit to stop further rendering in case of error
  } finally {
    Conexion::cerrar_conexion();
  }
  ?>

  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><?= htmlspecialchars($user->obtener_nombre_usuario(), ENT_QUOTES, 'UTF-8') ?></h1>
        </div>
        <div class="col-sm-6">
          <!-- Placeholder for future content -->
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
              <h3 class="card-title">
                <i class="fas fa-key" aria-hidden="true"></i> Update password
              </h3>
            </div>
            <form id="update-password-form" role="form" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
              <?php include_once 'forms/user/update_password.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- External JS file -->
<script src="<?= RUTA_JS; ?>users.js"></script>