<?php
// Check if the session is active, otherwise redirect to the home page
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
          <h1>Edit Provider Subitem</h1>
        </div>
        <div class="col-md-6 text-right">
          <!-- Reserved space for future buttons or options, if needed -->
        </div>
      </div>
    </div>
  </section>

  <!-- Main Content Section -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the data</h3>
            </div>
            <!-- Form to edit the provider subitem -->
            <form role="form" method="post" action="<?= htmlspecialchars(GUARDAR_EDIT_PROVIDER_SUBITEM . $id_provider_subitem); ?>">
              <?php
              // Include the form for editing the provider subitem
              include_once 'forms/quote/edicion_provider_subitem_vacio.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>