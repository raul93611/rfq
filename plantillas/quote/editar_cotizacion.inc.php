<?php
// Check session and redirect if not logged in
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}

// Open database connection
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

// Retrieve necessary data
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($conexion, $id_rfq);
$total_services = ServiceRepository::get_total($conexion, $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id($conexion, $cotizacion_recuperada->obtener_usuario_designado());

// Close database connection
Conexion::cerrar_conexion();

// Redirect to error page if quote is not found
if (is_null($cotizacion_recuperada)) {
  Redireccion::redirigir1(ERROR);
}
?>
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-3">
          <h1>Proposal # <?= htmlspecialchars($cotizacion_recuperada->obtener_id(), ENT_QUOTES, 'UTF-8'); ?></h1>
          <p>
            <strong><i class="fas fa-file-contract"></i> Contract Type:</strong>
            <?= htmlspecialchars($cotizacion_recuperada->obtener_type_of_contract() ?? 'No contract type specified', ENT_QUOTES, 'UTF-8'); ?>
          </p>
          <?php
          include 'templates/link_quote.inc.php';
          include 'templates/multi_year_project_dropdown.inc.php';
          ?>
        </div>
        <div class="col-md-7 text-center">
          <?php
          include 'plantillas/quote/templates/copy_quote_button.inc.php';
          include 'plantillas/quote/templates/comments_button.inc.php';
          ?>
          <button id="audit_trails_button" type="button" class="btn btn-secondary">Audit Trails</button>
        </div>
        <div class="col-md-2">
          <?php include 'plantillas/quote/templates/status_title.inc.php'; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <!-- Quote Edit Form -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the Data</h3>
            </div>
            <form role="form" id="form_edited_quote" method="post" enctype="multipart/form-data" action="<?= htmlspecialchars(GUARDAR_EDITAR_COTIZACION . $id_rfq); ?>">
              <?php include 'forms/quote/edicion_cotizacion_recuperada.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modals -->
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
?>

<!-- Scripts -->
<script src="<?= RUTA_JS; ?>services.js"></script>
<script src="<?= RUTA_JS; ?>quote.js"></script>
<script src="<?= RUTA_JS; ?>rooms.js"></script>