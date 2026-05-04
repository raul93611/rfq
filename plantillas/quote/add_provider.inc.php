<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
Conexion::abrir_conexion();
$item_provider            = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
$cotizacion_add_provider  = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item_provider->obtener_id_rfq());
Conexion::cerrar_conexion();
?>

<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Add Provider</h1>
      <p class="page-subtitle">
        Proposal #<?= htmlspecialchars($item_provider->obtener_id_rfq()); ?>
        &mdash; <?= htmlspecialchars($cotizacion_add_provider->obtener_email_code()); ?>
      </p>
    </div>
    <a href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($item_provider->obtener_id_rfq()); ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Back to Quote
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
          <div class="chart-card">
            <form role="form" method="post" action="<?= htmlspecialchars(GUARDAR_ADD_PROVIDER . $id_item); ?>">
              <?php include_once 'forms/quote/registro_provider_vacio.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>