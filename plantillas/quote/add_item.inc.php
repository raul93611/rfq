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
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1>Add Item</h1>
        </div>
        <div class="col-md-6">
          <!-- Additional content like buttons or breadcrumb navigation can be added here -->
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
            <!-- Form Start -->
            <form role="form" method="post" action="<?= htmlspecialchars(GUARDAR_ADD_ITEM . $id_rfq); ?>">
              <?php
              // Include the form for entering new item details
              include_once 'forms/quote/registro_item_vacio.inc.php';
              ?>
            </form>
            <!-- Form End -->
          </div>
        </div>
      </div>
    </div>
  </section>
</div>