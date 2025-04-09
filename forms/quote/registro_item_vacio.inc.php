<?php
Conexion::abrir_conexion();
$rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_rfq" value="<?= $id_rfq; ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <h2>Project specifications</h2>
      <div class="form-group">
        <label for="brand_project">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus>
        <small class="form-text text-muted">Enter the project brand name</small>
      </div>
      <div class="form-group">
        <label for="part_number_project">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part # ...">
        <small class="form-text text-muted">Enter the part number for the project</small>
      </div>
      <div class="form-group">
        <label for="description_project">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"></textarea>
        <small class="form-text text-muted">Provide a brief description of the project</small>
      </div>
    </div>
    <div class="col-md-6">
      <h2>E-logic proposal</h2>
      <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." autofocus>
        <small class="form-text text-muted">Enter the brand for the proposal</small>
      </div>
      <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part # ...">
        <small class="form-text text-muted">Enter the part number for the proposal</small>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"></textarea>
        <small class="form-text text-muted">Provide a description for the proposal</small>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" class="form-control form-control-sm" id="quantity" name="quantity">
    <small class="form-text text-muted">Specify the quantity for the proposal</small>
  </div>
  <div class="form-group">
    <label for="comments">Comments:</label>
    <textarea class="summernote_textarea form-control form-control-sm" rows="5" placeholder="Enter comments ..." id="comments" name="comments"></textarea>
    <small class="form-text text-muted">Additional comments for the proposal</small>
  </div>
  <div class="form-group">
    <label for="website">Website:</label>
    <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="Website ...">
    <small class="form-text text-muted">Enter the website related to the project</small>
  </div>
  <?php if (count($rooms)) : ?>
    <div class="form-group">
      <label for="id_room">Room:</label>
      <select class="custom-select" name="id_room">
        <option selected value="">Open this select menu</option>
        <?php foreach ($rooms as $key => $room) : ?>
          <option value="<?= $room->getId(); ?>"><?= $room->getName() ?></option>
        <?php endforeach; ?>
      </select>
      <small class="form-text text-muted">Select the room associated with the project</small>
    </div>
  <?php endif; ?>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-primary" name="guardar_item"><i class="fa fa-check"></i> Save</button>
  <a href="<?= EDITAR_COTIZACION . '/' . $id_rfq; ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a>
</div>