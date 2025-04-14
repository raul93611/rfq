<?php
try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch quote and re-quote details
  $quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
  $id_requote = ReQuoteRepository::create_re_quote($conexion, $id_rfq);
  $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($conexion, $id_rfq);
  $total_service = ServiceRepository::get_total($conexion, $id_rfq);
} catch (Exception $e) {
  // Handle errors and display an appropriate message
  die('Error retrieving data: ' . $e->getMessage());
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-2">
          <h1>Re-quote</h1>
        </div>
        <div class="col-sm-10 text-center">
          <button type="button" class="btn btn-secondary" id="audit_trails_button">Audit Trails</button>
          <a href="<?= htmlspecialchars(RELOAD_REQUOTE . $id_rfq); ?>" class="btn btn-secondary">Reload</a>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form id="re_quote_form" action="<?= htmlspecialchars(SAVE_RE_QUOTE); ?>" method="post">
            <div class="card card-primary">
              <input type="hidden" name="id_re_quote" value="<?= htmlspecialchars($re_quote->get_id()); ?>">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the data</h3>
              </div>
              <div class="card-body">
                <?php
                ReQuoteItemRepository::print_re_quote_items($re_quote->get_id());
                ?>
              </div>
            </div>

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Total Services</h3>
              </div>
              <div class="card-body">
                <?php include_once 're_quote_services.inc.php'; ?>
              </div>
            </div>

            <div class="card-footer footer_item">
              <a class="btn btn-secondary" id="go_back" href="<?= htmlspecialchars(EDITAR_COTIZACION . '/' . $re_quote->get_id_rfq()); ?>">
                <i class="fa fa-reply"></i>
              </a>
              <button type="submit" class="btn btn-primary" name="save_re_quote">
                <i class="fas fa-check"></i> Save
              </button>
            </div>
            <div class="px-2 py-2 card-footer footer_totals d-inline-flex">
              <div class="d-flex flex-nowrap align-items-center">
                <div class="px-4">
                  <h5 class="mb-0">
                    <small class="text-info">Total Price:</small><br>
                    <span>$<?= number_format($quote->obtener_quote_total_price(), 2); ?></span>
                  </h5>
                </div>
                <div class="px-4 border-left">
                  <h5 class="mb-0">
                    <small class="text-info">Total Profit:</small><br>
                    <span>$<?= number_format($quote->obtener_re_quote_profit(), 2); ?></span>
                  </h5>
                </div>
                <div class="px-4 border-left">
                  <h5 class="mb-0">
                    <small class="text-info">Profit (%):</small><br>
                    <span><?= number_format($quote->obtener_re_quote_profit_percentage(), 2); ?>%</span>
                  </h5>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<?php
// Include modals
include_once 'plantillas/re_quote/modals/audit_trails_modal.inc.php';
include_once 'modals/edit_service_modal.inc.php';
?>

<script src="<?= htmlspecialchars(RUTA_JS); ?>reQuote.js"></script>