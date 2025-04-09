<?php
Conexion::abrir_conexion();
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item->obtener_id_rfq());
$rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $item->obtener_id_rfq());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_item" value="<?= htmlspecialchars($id_item); ?>">
<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($item->obtener_id_rfq()); ?>">

<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <h2>Project Specifications</h2>
      <div class="form-group">
        <label for="brand_project">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus value="<?= htmlspecialchars($item->obtener_brand_project()); ?>">
        <small class="form-text text-muted">Enter the brand name associated with the project.</small>
        <input type="hidden" name="brand_project_original" value="<?= htmlspecialchars($item->obtener_brand_project()); ?>">
      </div>

      <div class="form-group">
        <label for="part_number_project">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part # ..." value="<?= htmlspecialchars($item->obtener_part_number_project()); ?>">
        <small class="form-text text-muted">Enter the part number of the project.</small>
        <input type="hidden" name="part_number_project_original" value="<?= htmlspecialchars($item->obtener_part_number_project()); ?>">
      </div>

      <div class="form-group">
        <label for="description_project">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"><?= htmlspecialchars($item->obtener_description_project()); ?></textarea>
        <small class="form-text text-muted">Provide a detailed description of the project.</small>
        <input type="hidden" name="description_project_original" value="<?= htmlspecialchars($item->obtener_description_project()); ?>">
      </div>
    </div>

    <div class="col-md-6">
      <h2>E-logic Proposal</h2>
      <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." value="<?= htmlspecialchars($item->obtener_brand()); ?>">
        <small class="form-text text-muted">Enter the brand for the E-logic proposal.</small>
        <input type="hidden" name="brand_original" value="<?= htmlspecialchars($item->obtener_brand()); ?>">
      </div>

      <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part # ..." value="<?= htmlspecialchars($item->obtener_part_number()); ?>">
        <small class="form-text text-muted">Enter the part number for the E-logic proposal.</small>
        <input type="hidden" name="part_number_original" value="<?= htmlspecialchars($item->obtener_part_number()); ?>">
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"><?= htmlspecialchars($item->obtener_description()); ?></textarea>
        <small class="form-text text-muted">Provide a description of the E-logic proposal.</small>
        <input type="hidden" name="description_original" value="<?= htmlspecialchars($item->obtener_description()); ?>">
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" required value="<?= htmlspecialchars($item->obtener_quantity()); ?>">
    <small class="form-text text-muted">Enter the quantity required for this item.</small>
    <input type="hidden" name="quantity_original" value="<?= htmlspecialchars($item->obtener_quantity()); ?>">
  </div>

  <div class="form-group">
    <label for="comments">Comments:</label>
    <textarea class="summernote_textarea form-control form-control-sm" rows="5" placeholder="Enter comments ..." id="comments" name="comments"><?= htmlspecialchars($item->obtener_comments()); ?></textarea>
    <small class="form-text text-muted">Any additional comments or notes regarding this item.</small>
    <textarea name="comments_original" rows="8" cols="80" style="display:none;"><?= htmlspecialchars($item->obtener_comments()); ?></textarea>
  </div>

  <div class="form-group">
    <label for="website">Website:</label>
    <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="Website ..." value="<?= htmlspecialchars($item->obtener_website()); ?>">
    <small class="form-text text-muted">Enter the associated website URL.</small>
    <input type="hidden" name="website_original" value="<?= htmlspecialchars($item->obtener_website()); ?>">
  </div>

  <?php if (count($rooms)) : ?>
    <div class="form-group">
      <label for="id_room">Room:</label>
      <select class="custom-select" name="id_room">
        <option value="">Open this select menu</option>
        <?php foreach ($rooms as $room) : ?>
          <option value="<?= htmlspecialchars($room->getId()); ?>" <?= $room->getId() == $item->getIdRoom() ? 'selected' : ''; ?>>
            <?= htmlspecialchars($room->getName()); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <small class="form-text text-muted">Select the room associated with this item.</small>
    </div>
  <?php endif; ?>
</div>

<div class="card-footer">
  <button type="submit" class="btn btn-primary" name="guardar_cambios_item">
    <i class="fa fa-check"></i> Save
  </button>
  <a href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($item->obtener_id_rfq()); ?>" class="btn btn-secondary">
    <i class="fa fa-times"></i> Cancel
  </a>
</div>