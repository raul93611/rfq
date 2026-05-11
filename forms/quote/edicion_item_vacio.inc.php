<?php
Conexion::abrir_conexion();
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item->obtener_id_rfq());
$rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $item->obtener_id_rfq());
Conexion::cerrar_conexion();
?>
<?php
// Update the subtitle and add back button now that we have the data
$_rfq_id   = htmlspecialchars($item->obtener_id_rfq());
$_rfq_code = htmlspecialchars($cotizacion_recuperada->obtener_email_code());
$_back_url = EDITAR_COTIZACION . '/' . $_rfq_id;
?>
<script>
(function(){
  var el = document.getElementById('edit-item-subtitle');
  if (el) el.textContent = 'Proposal #<?= $_rfq_id ?> — <?= addslashes($_rfq_code) ?>';
})();
</script>

<input type="hidden" name="id_item" value="<?= htmlspecialchars($id_item); ?>">
<input type="hidden" name="id_rfq"  value="<?= $_rfq_id; ?>">

<div class="card-body user-form">

  <!-- Two-panel comparison layout -->
  <div class="item-comparison-grid">
    <!-- Project Specifications -->
    <div class="item-panel item-panel--project">
      <div class="item-panel-header">
        <i class="fas fa-file-alt mr-2"></i> Project Specifications
      </div>
      <div class="item-panel-body">
        <div class="form-group">
          <label for="brand_project">Brand</label>
          <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus value="<?= htmlspecialchars($item->obtener_brand_project()); ?>">
          <input type="hidden" name="brand_project_original" value="<?= htmlspecialchars($item->obtener_brand_project()); ?>">
        </div>
        <div class="form-group">
          <label for="part_number_project">Part #</label>
          <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part number ..." value="<?= htmlspecialchars($item->obtener_part_number_project()); ?>">
          <input type="hidden" name="part_number_project_original" value="<?= htmlspecialchars($item->obtener_part_number_project()); ?>">
        </div>
        <div class="form-group mb-0">
          <label for="description_project">Description</label>
          <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"><?= htmlspecialchars($item->obtener_description_project()); ?></textarea>
          <input type="hidden" name="description_project_original" value="<?= htmlspecialchars($item->obtener_description_project()); ?>">
        </div>
      </div>
    </div>

    <!-- E-logic Proposal -->
    <div class="item-panel item-panel--proposal">
      <div class="item-panel-header">
        <i class="fas fa-lightbulb mr-2"></i> E-logic Proposal
      </div>
      <div class="item-panel-body">
        <div class="form-group">
          <label for="brand">Brand</label>
          <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." value="<?= htmlspecialchars($item->obtener_brand()); ?>">
          <input type="hidden" name="brand_original" value="<?= htmlspecialchars($item->obtener_brand()); ?>">
        </div>
        <div class="form-group">
          <label for="part_number">Part #</label>
          <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part number ..." value="<?= htmlspecialchars($item->obtener_part_number()); ?>">
          <input type="hidden" name="part_number_original" value="<?= htmlspecialchars($item->obtener_part_number()); ?>">
        </div>
        <div class="form-group mb-0">
          <label for="description">Description</label>
          <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"><?= htmlspecialchars($item->obtener_description()); ?></textarea>
          <input type="hidden" name="description_original" value="<?= htmlspecialchars($item->obtener_description()); ?>">
        </div>
      </div>
    </div>
  </div>

  <!-- Additional fields -->
  <div class="item-extra-fields">
    <div class="form-row">
      <div class="form-group col-md-4">
        <label for="quantity">Quantity</label>
        <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" required value="<?= htmlspecialchars($item->obtener_quantity()); ?>">
        <input type="hidden" name="quantity_original" value="<?= htmlspecialchars($item->obtener_quantity()); ?>">
      </div>
      <div class="form-group col-md-8">
        <label for="website">Website</label>
        <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="https://..." value="<?= htmlspecialchars($item->obtener_website()); ?>">
        <input type="hidden" name="website_original" value="<?= htmlspecialchars($item->obtener_website()); ?>">
      </div>
    </div>
    <?php if (count($rooms)) : ?>
    <div class="form-group">
      <label for="id_room">Room</label>
      <select class="form-control form-control-sm" name="id_room" id="id_room">
        <option value="">— Select a room —</option>
        <?php foreach ($rooms as $room) : ?>
          <option value="<?= htmlspecialchars($room->getId()); ?>" <?= $room->getId() == $item->getIdRoom() ? 'selected' : ''; ?>>
            <?= htmlspecialchars($room->getName()); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php endif; ?>
    <div class="form-group mb-0">
      <label for="comments">Comments</label>
      <textarea class="summernote_textarea form-control form-control-sm" rows="4" placeholder="Additional comments ..." id="comments" name="comments"><?= htmlspecialchars($item->obtener_comments()); ?></textarea>
      <textarea name="comments_original" rows="8" cols="80" style="display:none;"><?= htmlspecialchars($item->obtener_comments()); ?></textarea>
    </div>
  </div>

</div>
<div class="card-footer d-flex justify-content-between align-items-center" style="gap:8px;">
  <a href="<?= $_back_url; ?>" class="btn btn-secondary btn-sm">
    <i class="fas fa-arrow-left mr-1"></i> Back to Quote
  </a>
  <button type="submit" class="btn btn-primary btn-sm" name="guardar_cambios_item">
    <i class="fa fa-check mr-1"></i> Save Changes
  </button>
</div>