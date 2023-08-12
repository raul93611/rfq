<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Users</h1>
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
              <h3 class="card-title"><i class="fa fa-user-plus"></i> Sign in</h3>
            </div>
            <form id="add-user-form" role="form" method="post" action="">
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
<script src="<?php echo RUTA_JS; ?>users.js"></script>