<?php
// Ensure session is started, if not, redirect to the server homepage
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
          <h1>Add Subitem</h1>
        </div>
        <div class="col-md-6">
          <!-- Optional space for breadcrumbs, buttons, etc. -->
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
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the Data</h3>
            </div>
            <!-- Form Start -->
            <form role="form" method="post" action="<?= htmlspecialchars(GUARDAR_ADD_SUBITEM . $id_item); ?>">
              <?php
              // Include form fields for adding a subitem
              include_once 'forms/quote/registro_subitem_vacio.inc.php';
              ?>
            </form>
            <!-- Form End -->
          </div>
        </div>
      </div>
    </div>
  </section>
</div>