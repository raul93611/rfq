<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1>Edit Subitem</h1>
        </div>
        <div class="col-md-6">

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
            <form role="form" method="post" action="<?php echo GUARDAR_EDIT_SUBITEM . $id_subitem; ?>">
              <?php
              include_once 'forms/edicion_subitem_vacio.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
