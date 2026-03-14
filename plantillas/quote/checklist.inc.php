<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>

<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Checklist</h1>
      <p class="page-subtitle">Proposal #<?= htmlspecialchars($id_rfq); ?></p>
    </div>
    <a target="_blank" href="<?= htmlspecialchars(GENERATE_CHECKLIST_PDF . $id_rfq); ?>" class="btn btn-primary btn-sm">
      <i class="fas fa-file-pdf mr-1"></i> PDF
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="chart-card">
        <form role="form" id="checklist_form" method="post" action="<?= htmlspecialchars(SAVE_CHECKLIST . $id_rfq); ?>">
          <?php include_once 'forms/quote/checklist.inc.php'; ?>
        </form>
      </div>
    </div>
  </section>
</div>
