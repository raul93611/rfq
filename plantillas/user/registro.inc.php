<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Users</h1>
        </div>
        <div class="col-sm-6">
          <!-- Future content or buttons can go here -->
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
                <i class="fa fa-user-plus" aria-hidden="true"></i> Sign in
              </h3>
            </div>
            <form id="add-user-form" role="form" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
              <?php
              include_once 'forms/user/registro_usuario_vacio.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- External JS file -->
<script src="<?= RUTA_JS; ?>users.js"></script>