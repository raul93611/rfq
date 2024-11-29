<?php
// Open a database connection and retrieve the quote by its ID
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>

<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1>Checklist</h1>
        </div>
        <div class="col-md-6 text-right">
          <!-- Link to generate a PDF version of the checklist -->
          <a target="_blank" href="<?= htmlspecialchars(GENERATE_CHECKLIST_PDF . $id_rfq); ?>" class="btn btn-primary">
            <i class="fas fa-file"></i> PDF
          </a>
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
            <!-- Form to save checklist data -->
            <form role="form" id="checklist_form" method="post" action="<?= htmlspecialchars(SAVE_CHECKLIST . $id_rfq); ?>">
              <?php
              // Include the checklist form fields
              include_once 'forms/quote/checklist.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>