<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
Conexion::abrir_conexion();
$_hdr_subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $id_subitem);
$_hdr_item    = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $_hdr_subitem->obtener_id_item());
$_hdr_rfq     = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_hdr_item->obtener_id_rfq());
Conexion::cerrar_conexion();
$_hdr_back    = EDITAR_COTIZACION . '/' . $_hdr_item->obtener_id_rfq();
?>
<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Edit Subitem</h1>
      <p class="page-subtitle">Proposal #<?= htmlspecialchars($_hdr_item->obtener_id_rfq()); ?> &mdash; <?= htmlspecialchars($_hdr_rfq->obtener_email_code()); ?></p>
    </div>
    <a href="<?= $_hdr_back; ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Back to Quote
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
          <div class="chart-card">
            <form role="form" method="post" action="<?= htmlspecialchars(GUARDAR_EDIT_SUBITEM . $id_subitem); ?>">
              <?php include_once 'forms/quote/edicion_subitem_vacio.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>