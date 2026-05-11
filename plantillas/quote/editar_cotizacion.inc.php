<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($conexion, $id_rfq);
$total_services = ServiceRepository::get_total($conexion, $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id($conexion, $cotizacion_recuperada->obtener_usuario_designado());
Conexion::cerrar_conexion();

if (is_null($cotizacion_recuperada)) {
  Redireccion::redirigir1(ERROR);
}
?>
<div class="content-wrapper">

  <div class="content-header page-header-bar" style="align-items:flex-start;">
    <div>
      <h1 class="page-title">
        Proposal #<?= htmlspecialchars($cotizacion_recuperada->obtener_id(), ENT_QUOTES, 'UTF-8'); ?>
      </h1>
      <p class="page-subtitle" style="margin-bottom:6px;">
        <?= htmlspecialchars($cotizacion_recuperada->obtener_type_of_contract() ?? 'No contract type specified', ENT_QUOTES, 'UTF-8'); ?>
      </p>
      <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-top:4px;">
        <?php
        include 'templates/link_quote.inc.php';
        include 'templates/multi_year_project_dropdown.inc.php';
        ?>
      </div>
    </div>

    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;">
      <?php include 'plantillas/quote/templates/status_title.inc.php'; ?>
      <div style="display:flex;gap:6px;flex-wrap:wrap;justify-content:flex-end;">
        <?php
        include 'plantillas/quote/templates/copy_quote_button.inc.php';
        include 'plantillas/quote/templates/comments_button.inc.php';
        ?>
        <button id="audit_trails_button" type="button" class="btn btn-secondary btn-sm"
                data-id="<?= htmlspecialchars($id_rfq, ENT_QUOTES, 'UTF-8'); ?>">
          <i class="fas fa-history mr-1"></i> Audit Trails
        </button>
      </div>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="chart-card">
        <form role="form" id="form_edited_quote" method="post" enctype="multipart/form-data"
              action="<?= htmlspecialchars(GUARDAR_EDITAR_COTIZACION . $id_rfq); ?>">
          <?php include 'forms/quote/edicion_cotizacion_recuperada.inc.php'; ?>
        </form>
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
include_once 'modals/rooms/add_room_modal.inc.php';
include_once 'modals/rooms/edit_room_modal.inc.php';
include_once 'modals/import_items_modal.inc.php';
include_once 'plantillas/quote/modals/add_item_modal.inc.php';
include_once 'plantillas/quote/modals/edit_item_modal.inc.php';
include_once 'plantillas/quote/modals/add_subitem_modal.inc.php';
include_once 'plantillas/quote/modals/edit_subitem_modal.inc.php';
include_once 'plantillas/quote/modals/add_provider_modal.inc.php';
include_once 'plantillas/quote/modals/edit_provider_modal.inc.php';
include_once 'plantillas/quote/modals/add_provider_subitem_modal.inc.php';
include_once 'plantillas/quote/modals/edit_provider_subitem_modal.inc.php';
?>

<script src="<?= RUTA_JS; ?>services.js"></script>
<script src="<?= RUTA_JS; ?>quote.js"></script>
<script src="<?= RUTA_JS; ?>rooms.js"></script>
<script src="<?= RUTA_JS; ?>audit_trail.js"></script>
