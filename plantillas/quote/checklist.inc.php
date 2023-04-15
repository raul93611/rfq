<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1>Checklist</h1>
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
            <form role="form" id="checklist_form" method="post" action="<?php echo SAVE_CHECKLIST . $id_rfq; ?>">
              <?php
              include_once 'forms/quote/checklist.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
