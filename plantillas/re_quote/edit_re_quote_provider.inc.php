<?php
Conexion::abrir_conexion();
$_rq_prov = ReQuoteProviderRepository::get_re_quote_provider_by_id(Conexion::obtener_conexion(), $id_re_quote_provider);
$_rq_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $_rq_prov->get_id_re_quote_item());
$_rq      = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $_rq_item->get_id_re_quote());
$_rfq_id  = $_rq->get_id_rfq();
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Edit Provider</h1>
      <p class="page-subtitle">Proposal #<?= htmlspecialchars($_rfq_id); ?></p>
    </div>
    <a href="<?= RE_QUOTE . $_rfq_id; ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Back to Re-Quote
    </a>
  </div>
  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
          <div class="chart-card">
            <form role="form" method="post" action="<?= SAVE_EDIT_RE_QUOTE_PROVIDER; ?>">
              <?php include_once 'forms/re_quote/edit_re_quote_provider_form.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
