<?php
Conexion::abrir_conexion();
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_usuario_designado());
$total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
$re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
$items_exists = RepositorioItem::items_exists(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
Conexion::cerrar_conexion();
if (is_null($cotizacion_recuperada)) Redireccion::redirigir1(ERROR);
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-3">
          <h1>Proposal # <?= $cotizacion_recuperada->obtener_id(); ?>
            <?php include_once 'templates/link_quote.inc.php'; ?>
            <?php include_once 'templates/multi_year_project_dropdown.inc.php'; ?>
          </h1>
        </div>
        <div class="col-md-7 text-center">
          <?php
          include_once 'plantillas/quote/templates/copy_quote_button.inc.php';
          include_once 'plantillas/quote/templates/comments_button.inc.php';
          ?>
          <button id="audit_trails_button" type="button" name="button" class="btn btn-info">Audit Trails</button>
        </div>
        <div class="col-md-2">
          <?php
          include_once 'plantillas/quote/templates/status_title.inc.php';
          ?>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-body">
              <?php include_once 'templates/main_info.inc.php' ?>
            </div>
          </div>
          <div class="card card-primary">
            <div class="card-body">
              <?php include_once 'templates/file_manager.inc.php' ?>
            </div>
          </div>
          <div class="card card-primary" id="quote-table" data-id="<?= $id_rfq ?>">
          </div>
          <div class="card card-primary">
            <div class="card-body">
              <?php include_once 'forms/quote/templates/status_checkbox.inc.php'; ?>
            </div>
          </div>
          <div class="card-footer footer_item" id="footer_lg">
            <?php include_once 'forms/quote/templates/go_back_button.inc.php'; ?>
            <?php include_once 'forms/quote/templates/add_item.inc.php'; ?>
            <a href="#" id="add_comment" class="btn btn-primary add_item_charter"><i class="fas fa-plus"></i> Add comment</a>
            <?php include_once 'forms/quote/templates/actions_button.inc.php'; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php
include_once 'plantillas/quote/modals/new_comment_modal.inc.php';
include_once 'plantillas/quote/modals/comments_modal.inc.php';
include_once 'plantillas/quote/modals/audit_trails_modal.inc.php';
include_once 'plantillas/services/modals/add_service_modal.inc.php';
include_once 'plantillas/services/modals/edit_service_modal.inc.php';
include_once 'modals/type_of_contract_modal.inc.php';
include_once 'modals/sales_commission_modal.inc.php';
include_once 'modals/link_quote_modal.inc.php';
include_once 'modals/new_item_modal.inc.php';
include_once 'modals/edit_item_modal.inc.php';
include_once 'modals/add_provider_modal.inc.php';
include_once 'modals/edit_provider_modal.inc.php';
include_once 'modals/add_subitem_modal.inc.php';
include_once 'modals/edit_subitem_modal.inc.php';
include_once 'modals/add_subitem_provider_modal.inc.php';
include_once 'modals/edit_subitem_provider_modal.inc.php';
include_once 'modals/edit_taxes_modal.inc.php';
?>
<script src="<?php echo RUTA_JS; ?>services.js"></script>
<script src="<?php echo RUTA_JS; ?>quote.js"></script>