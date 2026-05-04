<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>

<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Information</h1>
      <p class="page-subtitle">Proposal #<?= htmlspecialchars($id_rfq); ?></p>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="chart-card">
        <form role="form" id="information_form" method="post" action="<?= htmlspecialchars(SAVE_INFORMATION . $id_rfq); ?>">
          <?php include_once 'forms/quote/information.inc.php'; ?>
        </form>
      </div>
    </div>
  </section>
</div>
