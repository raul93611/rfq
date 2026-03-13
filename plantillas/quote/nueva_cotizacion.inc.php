<?php
include_once 'plantillas/quote/validacion_registro_cotizacion.inc.php';
?>

<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">New Quote</h1>
      <p class="page-subtitle">Fill in the details to register a new bid or opportunity</p>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div class="card chart-card">
            <form role="form" method="post" enctype="multipart/form-data" action="<?= htmlspecialchars(NUEVA_COTIZACION); ?>">
              <?php
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
