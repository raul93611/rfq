<?php
// Ensure session is started, if not redirect to the server homepage
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}

// Open a database connection and retrieve the quote based on ID
Conexion::abrir_conexion();
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>

<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Add Item</h1>
      <p class="page-subtitle">Proposal #<?= htmlspecialchars($cotizacion_recuperada->obtener_id()); ?> &mdash; <?= htmlspecialchars($cotizacion_recuperada->obtener_email_code()); ?></p>
    </div>
    <a href="<?= EDITAR_COTIZACION . '/' . $id_rfq; ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Back to Quote
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
          <div class="chart-card">
            <form role="form" method="post" action="<?= htmlspecialchars(GUARDAR_ADD_ITEM . $id_rfq); ?>">
              <?php include_once 'forms/quote/registro_item_vacio.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>