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

// Deterministic color per Type of Contract value (free-form string, no fixed enum) —
// same hash + palette as categoryColor() in js/pipeline_metrics.js, so a type reads
// the same color here as it does on the Bid Pipeline Charts.
function contractTypeColor($name) {
  if (!$name) return '#9aa6b6';
  $palette = ['#2db4e8', '#4f6ef0', '#16a34a', '#d97706', '#7c5cf0', '#0e7490', '#14b8a6', '#f0734f', '#dc2626', '#1aa2dc', '#9333ea', '#b45309'];
  $hash = 0;
  foreach (str_split($name) as $ch) {
    $hash = ($hash * 31 + ord($ch)) & 0xFFFFFFFF;
  }
  return $palette[$hash % count($palette)];
}
?>
<div class="content-wrapper">

  <div class="content-header page-header-bar" style="align-items:flex-start;">
    <div>
      <h1 class="page-title">
        Proposal #<?= htmlspecialchars($cotizacion_recuperada->obtener_id(), ENT_QUOTES, 'UTF-8'); ?>
      </h1>
      <p class="page-subtitle" style="margin-bottom:6px;">
        <?php
        $contractType = $cotizacion_recuperada->obtener_type_of_contract();
        $contractTypeLabel = $contractType ?: 'No contract type specified';
        ?>
        <span class="contract-type-pill" style="--ct:<?= contractTypeColor($contractType); ?>;">
          <span class="contract-type-pill-dot"></span><?= htmlspecialchars($contractTypeLabel, ENT_QUOTES, 'UTF-8'); ?>
        </span>
      </p>
      <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-top:4px;">
        <?php
        include 'templates/link_quote.inc.php';
        include 'templates/multi_year_project_dropdown.inc.php';
        ?>
      </div>
      <?php include 'plantillas/quote/templates/partner_badges.inc.php'; ?>
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
include_once 'plantillas/quote/modals/checklist_information_drawer.inc.php';
include_once 'plantillas/services/modals/add_service_modal.inc.php';
include_once 'plantillas/services/modals/edit_service_modal.inc.php';
include_once 'modals/type_of_contract_modal.inc.php';
include_once 'modals/sales_commission_modal.inc.php';
include_once 'modals/link_quote_modal.inc.php';
include_once 'plantillas/quote/modals/sources_sought_modal.inc.php';
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

<script src="<?= asset_url('js/services.js'); ?>"></script>
<script src="<?= asset_url('js/payment_split.js'); ?>"></script>
<script src="<?= asset_url('js/items_table.js'); ?>"></script>
<script src="<?= asset_url('js/quote.js'); ?>"></script>
<script src="<?= asset_url('js/rooms.js'); ?>"></script>
<script src="<?= asset_url('js/audit_trail.js'); ?>"></script>
<script src="<?= asset_url('js/sources_sought.js'); ?>"></script>
<script src="<?= asset_url('js/partner_share.js'); ?>"></script>
<script src="<?= asset_url('js/checklist_info_drawer.js'); ?>"></script>
<script src="<?= asset_url('js/import_items_modal.js'); ?>"></script>
