<?php
// Open connection to the database
Conexion::abrir_conexion();

// Fetch all types of contracts
$type_of_contracts = TypeOfContractRepository::get_all(Conexion::obtener_conexion());

// Close the connection
Conexion::cerrar_conexion();
?>
<div class="modal fade" id="type_of_contract_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Type Of Contract</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="type_of_contract">Type Of Contract:</label>
          <select class="form-control form-control-sm" name="type_of_contract" id="type_of_contract" form="form_edited_quote">
            <?php foreach ($type_of_contracts as $type_of_contract): ?>
              <option value="<?= htmlspecialchars($type_of_contract->get_type_of_contract()); ?>">
                <?= htmlspecialchars($type_of_contract->get_type_of_contract()); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <small class="form-text text-muted">Please select the appropriate contract type from the list.</small>
        </div>
        <p><b>Note:</b> Reload the page to dismiss the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="submit" name="guardar_cambios_cotizacion" form="form_edited_quote" class="btn btn-success">
          <i class="fa fa-check"></i> Save
        </button>
      </div>
    </div>
  </div>
</div>