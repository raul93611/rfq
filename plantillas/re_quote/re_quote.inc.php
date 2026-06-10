<?php
try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();
  $quote    = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
  $id_requote = ReQuoteRepository::create_re_quote($conexion, $id_rfq);
  $re_quote   = ReQuoteRepository::get_re_quote_by_id_rfq($conexion, $id_rfq);
  $total_service = ServiceRepository::get_total($conexion, $id_rfq);
} catch (Exception $e) {
  die('Error retrieving data: ' . $e->getMessage());
} finally {
  Conexion::cerrar_conexion();
}
?>
<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Re-Quote</h1>
      <p class="page-subtitle">Proposal #<?= htmlspecialchars($quote->obtener_id()); ?> &mdash; <?= htmlspecialchars($quote->obtener_email_code()); ?></p>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
      <button type="button" class="btn btn-outline-secondary btn-sm" id="audit_trails_button" data-id="<?= htmlspecialchars($id_rfq, ENT_QUOTES, 'UTF-8'); ?>">
        <i class="fas fa-history mr-1"></i> Audit Trails
      </button>
      <a href="<?= htmlspecialchars(RELOAD_REQUOTE . $id_rfq); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-sync-alt mr-1"></i> Reload
      </a>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <form id="re_quote_form" action="<?= htmlspecialchars(SAVE_RE_QUOTE); ?>" method="post">
        <input type="hidden" name="id_re_quote" value="<?= htmlspecialchars($re_quote->get_id()); ?>">

        <!-- Items table card -->
        <div class="chart-card mb-4">
          <div class="card-body" style="padding:20px;">
            <?php ReQuoteItemRepository::print_re_quote_items($re_quote->get_id()); ?>
          </div>
        </div>

        <!-- Services card -->
        <div class="chart-card mb-4">
          <div class="card-body" style="padding:20px;">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8896a5;margin-bottom:14px;">
              <i class="fas fa-concierge-bell mr-1"></i> Total Services
            </div>
            <?php include_once 're_quote_services.inc.php'; ?>
          </div>
        </div>

        <!-- Sticky action bar: Back | Save | Totals -->
        <div class="quote-action-bar">
          <div class="quote-action-bar__left">
            <a class="btn btn-secondary btn-sm" href="<?= htmlspecialchars(EDITAR_COTIZACION . '/' . $re_quote->get_id_rfq()); ?>">
              <i class="fa fa-reply mr-1"></i> Back
            </a>
          </div>
          <div class="quote-action-bar__right">
            <button type="submit" class="btn btn-primary btn-sm" name="save_re_quote">
              <i class="fas fa-check mr-1"></i> Save
            </button>
          </div>
          <div class="quote-action-bar__totals">
            <div class="quote-action-total">
              <span class="quote-action-total__label">Total Price</span>
              <span class="quote-action-total__value">$<?= number_format($quote->obtener_quote_total_price(), 2); ?></span>
            </div>
            <div class="quote-action-total">
              <span class="quote-action-total__label">Total Profit</span>
              <span class="quote-action-total__value">$<?= number_format($quote->obtener_re_quote_profit(), 2); ?></span>
            </div>
            <div class="quote-action-total">
              <span class="quote-action-total__label">Profit %</span>
              <span class="quote-action-total__value"><?= number_format($quote->obtener_re_quote_profit_percentage(), 2); ?>%</span>
            </div>
          </div>
        </div>

      </form>
    </div>
  </section>
</div>

<?php
include_once 'plantillas/re_quote/modals/audit_trails_modal.inc.php';
include_once 'modals/edit_service_modal.inc.php';
?>

<script src="<?= asset_url('js/reQuote.js'); ?>"></script>
<script src="<?= asset_url('js/audit_trail.js'); ?>"></script>
