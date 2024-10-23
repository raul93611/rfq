<?php
// Check if session is active, otherwise redirect to the server
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
          <h1>Edit Subitem</h1>
        </div>
        <div class="col-md-6 text-right">
          <!-- Placeholder for future action buttons or links -->
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
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the Data</h3>
            </div>
            <!-- Form for editing subitem -->
            <form role="form" method="post" action="<?= htmlspecialchars(GUARDAR_EDIT_SUBITEM . $id_subitem); ?>">
              <?php
              // Include the form for editing subitem data
              include_once 'forms/quote/edicion_subitem_vacio.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>