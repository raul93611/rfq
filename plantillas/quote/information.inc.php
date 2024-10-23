<?php
// Open database connection
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

// Retrieve quote data
$quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);

// Close database connection
Conexion::cerrar_conexion();
?>

<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1>Information</h1>
        </div>
        <div class="col-md-6"></div>
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
            <form role="form" id="information_form" method="post" action="<?= htmlspecialchars(SAVE_INFORMATION . $id_rfq); ?>">
              <?php include_once 'forms/quote/information.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>