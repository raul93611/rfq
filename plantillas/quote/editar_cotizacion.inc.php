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

  <?php
  $ss_status    = $cotizacion_recuperada->getSheetSyncStatus();
  $ss_at        = $cotizacion_recuperada->getSheetSyncAt();
  $ss_opp       = $cotizacion_recuperada->getName();
  $ss_tone      = $ss_status ?? 'never';
  $ss_label_map = ['synced' => 'Synced', 'failed' => 'Sync failed', 'never' => 'Never synced'];
  $ss_label     = $ss_label_map[$ss_tone] ?? 'Never synced';
  $ss_btn_text  = $ss_status === 'synced' ? 'Re-sync' : ($ss_status === 'failed' ? 'Retry Sync' : 'Sync to Sheet');
  $ss_btn_class = $ss_status === 'synced' ? 'ss-btn-success' : ($ss_status === 'failed' ? 'ss-btn-danger' : 'ss-btn-neutral');
  $ss_at_fmt    = $ss_at ? date('M j, Y \a\t g:i A', strtotime($ss_at)) : null;

  $ss_icon_html = [
    'synced' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
    'failed' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    'never'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>',
  ];
  ?>
  <div class="container-fluid" style="padding-top:14px;padding-bottom:0;">
    <div id="ss-block" class="ss-block ss-block-<?= htmlspecialchars($ss_tone); ?>"
         data-id="<?= (int)$id_rfq; ?>">
      <div class="ss-block-icon ss-block-icon-<?= htmlspecialchars($ss_tone); ?>">
        <?= $ss_icon_html[$ss_tone] ?? $ss_icon_html['never']; ?>
      </div>
      <div class="ss-block-body">
        <div class="ss-block-label">Sheet Sync</div>
        <div class="ss-block-status-row">
          <span class="ss-block-status ss-block-status-<?= htmlspecialchars($ss_tone); ?>">
            <?= htmlspecialchars($ss_label); ?>
          </span>
          <?php if ($ss_status === 'synced' && $ss_at_fmt): ?>
            <span class="ss-block-meta">· Last synced <?= htmlspecialchars($ss_at_fmt); ?></span>
          <?php elseif ($ss_status === 'failed' && $ss_at_fmt): ?>
            <span class="ss-block-meta">· Last attempted <?= htmlspecialchars($ss_at_fmt); ?></span>
          <?php endif; ?>
        </div>
        <?php if ($ss_opp): ?>
          <div class="ss-block-opp">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><circle cx="7" cy="7" r="1.4" fill="currentColor"/></svg>
            Description: <strong><?= htmlspecialchars($ss_opp); ?></strong>
          </div>
        <?php endif; ?>
      </div>
      <button type="button" id="ss-sync-btn" class="ss-btn <?= htmlspecialchars($ss_btn_class); ?>">
        <svg id="ss-btn-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <?php if ($ss_status === 'failed'): ?>
            <path d="M3 12a9 9 0 1 0 3-6.7"/><polyline points="3 4 3 9 8 9"/>
          <?php elseif ($ss_status === 'synced'): ?>
            <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
          <?php else: ?>
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
          <?php endif; ?>
        </svg>
        <span id="ss-btn-label"><?= htmlspecialchars($ss_btn_text); ?></span>
      </button>
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
<script src="<?= RUTA_JS; ?>sheet_sync.js"></script>
