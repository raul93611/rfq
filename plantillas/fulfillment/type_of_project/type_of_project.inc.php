<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Type of Project</h1>
        </div>
        <div class="col-sm-6 text-right">
          <button id="add-type-of-project-button" class="btn btn-primary" type="button">
            <i class="fas fa-plus"></i> Add Project Type
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content Section -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Type of Project List</h3>
            </div>
            <div class="card-body">
              <table id="type-of-project-table" class="table table-bordered table-hover table-responsive-md">
                <thead class="thead-light">
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Dynamic content will be loaded here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modals -->
<?php
include_once 'modals/add_modal.inc.php';
include_once 'modals/edit_modal.inc.php';
?>

<!-- Scripts -->
<script src="<?= RUTA_JS; ?>typeOfProject.js"></script>