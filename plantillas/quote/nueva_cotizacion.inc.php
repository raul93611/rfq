<?php
include_once 'plantillas/quote/validacion_registro_cotizacion.inc.php';
?>

<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>New Quote</h1>
        </div>
        <div class="col-sm-6"></div>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the Data</h3>
            </div>
            <form role="form" method="post" enctype="multipart/form-data" action="<?= htmlspecialchars(NUEVA_COTIZACION); ?>">
              <?php
              // Include the appropriate form based on the registration status
              $formToInclude = isset($_POST['registrar_cotizacion'])
                ? 'forms/quote/registro_cotizacion_validado.inc.php'
                : 'forms/quote/registro_cotizacion_vacio.inc.php';

              include_once $formToInclude;
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>