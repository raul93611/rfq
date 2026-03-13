<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Types of Projects</h1>
      <p class="page-subtitle">Manage project type categories</p>
    </div>
    <button id="add-type-of-project-button" class="btn btn-primary btn-sm" type="button">
      <i class="fas fa-plus mr-1"></i>Add Project Type
    </button>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="card chart-card">
        <div class="card-body">
          <table id="type-of-project-table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
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

<script src="<?= RUTA_JS; ?>typeOfProject.js"></script>
