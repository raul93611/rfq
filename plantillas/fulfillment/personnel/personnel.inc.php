<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Personnel</h1>
      <p class="page-subtitle">Manage personnel records</p>
    </div>
    <button id="add-personnel-button" class="btn btn-primary btn-sm" type="button">
      <i class="fas fa-plus mr-1"></i>Add Personnel
    </button>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="card chart-card">
        <div class="card-body">
          <table id="personnel-table" class="table table-bordered table-hover">
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
              <!-- Populated dynamically -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

</div>

<?php
include_once 'modals/add_modal.inc.php';
include_once 'modals/edit_modal.inc.php';
?>

<script src="<?= asset_url('js/personnel.js'); ?>"></script>
