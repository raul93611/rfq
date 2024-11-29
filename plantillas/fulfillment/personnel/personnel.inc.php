<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Personnel</h1>
        </div>
        <div class="col-sm-6">
          <button id="add-personnel-button" class="float-right btn btn-primary" type="button" aria-label="Add Personnel">
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Personnel List</h3>
            </div>
            <div class="card-body">
              <table id="personnel-table" class="table table-bordered">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Criteria</th>
                    <th>Type</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Personnel data will be populated here -->
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php
// Include modals for adding and editing personnel
include_once 'modals/add_modal.inc.php';
include_once 'modals/edit_modal.inc.php';
?>

<!-- Include JavaScript file for personnel functionalities -->
<script src="<?= RUTA_JS; ?>personnel.js"></script>