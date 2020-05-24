<?php
include_once 'plantillas/validacion_registro_cotizacion.inc.php';
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>New quote</h1>
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
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the data</h3>
            </div>
            <form role="form" method="post" enctype="multipart/form-data" action="<?php echo NUEVA_COTIZACION; ?>">
              <?php
              if (isset($_POST['registrar_cotizacion'])) {
                include_once 'forms/registro_cotizacion_validado.inc.php';
              } else {
                include_once 'forms/registro_cotizacion_vacio.inc.php';
              }
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
