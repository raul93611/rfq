<?php
Conexion::abrir_conexion();
$rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_rfq" value="<?= $id_rfq; ?>">
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
          <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus>
        </div>
        <div class="form-group">
          <label for="part_number_project">Part #</label>
          <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part number ...">
        </div>
        <div class="form-group mb-0">
          <label for="description_project">Description</label>
          <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"></textarea>
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
          <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ...">
        </div>
        <div class="form-group">
          <label for="part_number">Part #</label>
          <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part number ...">
        </div>
        <div class="form-group mb-0">
          <label for="description">Description</label>
          <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"></textarea>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional fields -->
  <div class="item-extra-fields">
    <div class="form-row">
      <div class="form-group col-md-4">
        <label for="quantity">Quantity</label>
        <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" min="0">
      </div>
      <div class="form-group col-md-8">
        <label for="website">Website</label>
        <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="https://...">
      </div>
    </div>
    <?php if (count($rooms)) : ?>
    <div class="form-group">
      <label for="id_room">Room</label>
      <select class="form-control form-control-sm" name="id_room" id="id_room">
        <option value="">— Select a room —</option>
        <?php foreach ($rooms as $room) : ?>
          <option value="<?= $room->getId(); ?>"><?= htmlspecialchars($room->getName()) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php endif; ?>
    <div class="form-group mb-0">
      <label for="comments">Comments</label>
      <textarea class="summernote_textarea form-control form-control-sm" rows="4" placeholder="Additional comments ..." id="comments" name="comments"></textarea>
    </div>
  </div>

</div>
<div class="card-footer d-flex justify-content-end" style="gap:8px;">
  <a href="<?= EDITAR_COTIZACION . '/' . $id_rfq; ?>" class="btn btn-secondary btn-sm">
    <i class="fa fa-times mr-1"></i> Cancel
  </a>
  <button type="submit" class="btn btn-primary btn-sm" name="guardar_item">
    <i class="fa fa-check mr-1"></i> Save Item
  </button>
</div>