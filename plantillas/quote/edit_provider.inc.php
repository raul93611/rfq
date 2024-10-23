<?php
// Ensure the user session is active, otherwise redirect
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
?>
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1>Edit Provider</h1>
        </div>
        <div class="col-md-6 text-right">
          <!-- Reserved space for additional actions if necessary -->
        </div>
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
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the data</h3>
            </div>
            <!-- Form to edit provider -->
            <form role="form" method="post" action="<?= htmlspecialchars(GUARDAR_EDIT_PROVIDER . $id_provider); ?>">
              <?php
              // Include form for provider editing
              include_once 'forms/quote/edicion_provider_vacio.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>